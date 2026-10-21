<?php
/**
 * Scheda contatto "Salva il contatto": vCard 3.0 in UTF-8, generata al volo da Impostazioni landing.
 * Indirizzo: /?nl_vcard=1 (lo stesso codificato nel QR della sezione Contatto).
 * Servita in linea come text/vcard: iPhone mostra la scheda "Aggiungi contatto",
 * Android la scarica e la apre con l'app Contatti.
 */

defined( 'ABSPATH' ) || exit;

function nl_vcard_url() {
	return add_query_arg( 'nl_vcard', '1', home_url( '/' ) );
}

/**
 * Valore di testo vCard: barra rovesciata, virgola, punto e virgola e a capo vanno protetti.
 */
function nl_vcard_testo( $valore ) {
	return str_replace( array( '\\', ',', ';', "\r\n", "\n" ), array( '\\\\', '\\,', '\\;', '\\n', '\\n' ), (string) $valore );
}

/**
 * Righe più lunghe di 75 byte: si spezzano con a capo + spazio (RFC 2425), senza dividere i caratteri UTF-8.
 */
function nl_vcard_piega( $riga ) {
	$out = '';
	$len = 0;
	foreach ( preg_split( '//u', $riga, -1, PREG_SPLIT_NO_EMPTY ) as $car ) {
		$byte = strlen( $car );
		if ( $len + $byte > 75 ) {
			$out .= "\r\n ";
			$len  = 1;
		}
		$out .= $car;
		$len += $byte;
	}
	return $out;
}

/**
 * Foto del referente in JPEG 400 × 400, codificata in base64. Messa in cache finché la foto non cambia.
 * La vCard usa il JPEG perché non tutte le app Contatti leggono il WebP.
 */
function nl_vcard_foto() {
	$id   = (int) nl_opt( 'foto_id' );
	$file = $id ? get_attached_file( $id ) : '';
	if ( ! $file || ! file_exists( $file ) ) {
		return '';
	}
	$chiave = 'nl_vcard_jpeg_' . $id . '_' . filemtime( $file );
	$foto   = get_transient( $chiave );
	if ( false !== $foto ) {
		return $foto;
	}
	$foto   = '';
	$editor = wp_get_image_editor( $file );
	if ( ! is_wp_error( $editor ) ) {
		$editor->resize( 400, 400, true );
		$editor->set_quality( 80 );
		$tmp = trailingslashit( get_temp_dir() ) . 'nl-vcard-' . wp_generate_password( 8, false ) . '.jpg';
		// Il tema converte in WebP le immagini generate (functions.php): qui serve proprio il JPEG.
		$solo_jpeg = fn() => array();
		add_filter( 'image_editor_output_format', $solo_jpeg, 99 );
		$salvat = $editor->save( $tmp, 'image/jpeg' );
		remove_filter( 'image_editor_output_format', $solo_jpeg, 99 );
		if ( ! is_wp_error( $salvat ) && file_exists( $salvat['path'] ) ) {
			$foto = base64_encode( file_get_contents( $salvat['path'] ) ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions,WordPress.WP.AlternativeFunctions
			wp_delete_file( $salvat['path'] );
		}
	}
	set_transient( $chiave, $foto, MONTH_IN_SECONDS );
	return $foto;
}

function nl_vcard() {
	$nome    = trim( (string) nl_opt( 'referente_nome' ) );
	$parti   = preg_split( '/\s+/u', $nome );
	$cognome = count( $parti ) > 1 ? array_pop( $parti ) : '';
	$tel     = preg_replace( '/[^\d+]/', '', (string) nl_opt( 'telefono' ) );

	$righe = array(
		'BEGIN:VCARD',
		'VERSION:3.0',
		'N;CHARSET=UTF-8:' . nl_vcard_testo( $cognome ) . ';' . nl_vcard_testo( implode( ' ', $parti ) ) . ';;;',
		'FN;CHARSET=UTF-8:' . nl_vcard_testo( $nome ),
		'ORG;CHARSET=UTF-8:' . nl_vcard_testo( nl_opt( 'azienda_nome' ) ),
	);
	if ( nl_opt( 'referente_ruolo' ) ) {
		$righe[] = 'TITLE;CHARSET=UTF-8:' . nl_vcard_testo( nl_opt( 'referente_ruolo' ) );
	}
	if ( $tel ) {
		$righe[] = 'TEL;TYPE=CELL,VOICE:' . $tel;
	}
	if ( nl_opt( 'email' ) ) {
		$righe[] = 'EMAIL;TYPE=INTERNET,WORK:' . nl_opt( 'email' );
	}
	if ( nl_opt( 'sito_web' ) ) {
		$righe[] = 'URL:' . nl_opt( 'sito_web' );
	}
	$foto = nl_vcard_foto();
	if ( $foto ) {
		$righe[] = 'PHOTO;ENCODING=b;TYPE=JPEG:' . $foto;
	}
	$righe[] = 'END:VCARD';

	return implode( "\r\n", array_map( 'nl_vcard_piega', $righe ) ) . "\r\n";
}

add_action( 'template_redirect', function () {
	if ( empty( $_GET['nl_vcard'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification -- download pubblico, nessuna azione.
		return;
	}
	$file = sanitize_file_name( nl_opt( 'referente_nome' ) . ' ' . nl_opt( 'azienda_nome' ) ) . '.vcf';
	nocache_headers();
	header( 'Content-Type: text/vcard; charset=utf-8' );
	header( 'Content-Disposition: inline; filename="' . $file . '"' );
	header( 'X-Robots-Tag: noindex' );
	echo nl_vcard(); // phpcs:ignore WordPress.Security.EscapeOutput -- file vCard, non HTML.
	exit;
}, 1 );
