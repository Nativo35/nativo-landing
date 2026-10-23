<?php
/**
 * Visibilità delle sezioni della landing (Impostazioni landing › Sezioni).
 *
 * - Sezione disattivata: non viene generata e il suo CSS e JavaScript non vengono caricati.
 * - Sezione nascosta su un dispositivo: classi CSS legate ai breakpoint (niente riconoscimento
 *   del dispositivo lato server, così funziona anche con la cache dell'hosting).
 * - Da loggato le sezioni nascoste o disattivate restano visibili, con bordo tratteggiato ed etichetta.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Breakpoint dei dispositivi, in rem (1rem = 16px), uguali alle media query del tema:
 * Mobile fino a 47.99rem (767px), Tablet da 48rem (768px) a 63.99rem (1023px), Desktop da 64rem (1024px).
 * Unico punto in cui sono scritti per la visibilità delle sezioni.
 */
const NL_BREAKPOINT_TABLET  = 48;
const NL_BREAKPOINT_DESKTOP = 64;

/**
 * Dispositivi nell'ordine del pannello, con la media query di ciascuno.
 */
function nl_dispositivi() {
	$t = NL_BREAKPOINT_TABLET;
	$d = NL_BREAKPOINT_DESKTOP;
	return array(
		'desktop' => array( 'nome' => 'Desktop', 'px' => 'da ' . ( $d * 16 ) . ' px', 'media' => "(min-width: {$d}rem)" ),
		'tablet'  => array( 'nome' => 'Tablet', 'px' => ( $t * 16 ) . '–' . ( $d * 16 - 1 ) . ' px', 'media' => "(min-width: {$t}rem) and (max-width: " . ( $d - 0.01 ) . 'rem)' ),
		'mobile'  => array( 'nome' => 'Mobile', 'px' => 'fino a ' . ( $t * 16 - 1 ) . ' px', 'media' => '(max-width: ' . ( $t - 0.01 ) . 'rem)' ),
	);
}

/**
 * Sezioni nell'ordine della pagina.
 * - protetta: non si può disattivare (Hero e Form);
 * - dentro: blocco che sta dentro un'altra sezione ("Prezzi chiari, subito" nella hero, Numeri e Metodo nella sezione del referente);
 * - ancore: id dei link interni che portano alla sezione.
 */
function nl_sezioni_elenco() {
	return array(
		'hero'          => array( 'nome' => 'Hero', 'protetta' => true ),
		'prezzi_lampo'  => array( 'nome' => 'Prezzi chiari, subito', 'dentro' => 'hero' ),
		'problemi'      => array( 'nome' => 'Problemi', 'ancore' => array( 'problemi' ) ),
		'soluzione'     => array( 'nome' => 'Cosa ottieni', 'ancore' => array( 'soluzione' ) ),
		'pacchetti'     => array( 'nome' => 'Pacchetti', 'ancore' => array( 'pacchetti' ) ),
		'servizi'       => array( 'nome' => 'Servizi aggiuntivi', 'ancore' => array( 'servizi' ) ),
		'progetti'      => array( 'nome' => 'Progetti', 'ancore' => array( 'progetti' ) ),
		'referente'     => array( 'nome' => 'Referente unico', 'ancore' => array( 'referente' ) ),
		'numeri'        => array( 'nome' => 'Numeri', 'dentro' => 'referente' ),
		'metodo'        => array( 'nome' => 'Metodo', 'dentro' => 'referente' ),
		'territorio'    => array( 'nome' => 'Cartina della Toscana', 'ancore' => array( 'territorio' ) ),
		'testimonianze' => array( 'nome' => 'Testimonianze', 'ancore' => array( 'testimonianze' ) ),
		'faq'           => array( 'nome' => 'FAQ', 'ancore' => array( 'faq' ) ),
		'preventivo'    => array( 'nome' => 'Form', 'protetta' => true, 'ancore' => array( 'preventivo', 'modulo' ) ),
		'contatto'      => array( 'nome' => 'Non ancora pronto', 'ancore' => array( 'contatto' ) ),
	);
}

/**
 * Impostazioni di tutte le sezioni: attiva e visibilità per dispositivo (1/0).
 * Prima del primo salvataggio della scheda "Sezioni" valgono i vecchi interruttori
 * di Servizi aggiuntivi, Progetti e Cartina.
 */
function nl_sezioni_config() {
	static $config = null;
	if ( null !== $config ) {
		return $config;
	}
	$salvate = nl_opt( 'sezioni' );
	$vecchi  = array(
		'servizi'    => nl_opt( 'servizi_attivi' ),
		'progetti'   => nl_opt( 'progetti_attivi' ),
		'territorio' => nl_opt( 'territorio_attivo' ),
	);

	$config = array();
	foreach ( nl_sezioni_elenco() as $chiave => $sezione ) {
		$riga = is_array( $salvate ) && isset( $salvate[ $chiave ] ) ? $salvate[ $chiave ] : array(
			'attiva'  => isset( $vecchi[ $chiave ] ) ? (int) (bool) $vecchi[ $chiave ] : 1,
			'desktop' => 1,
			'tablet'  => 1,
			'mobile'  => 1,
		);
		$config[ $chiave ] = array(
			'attiva'  => ! empty( $sezione['protetta'] ) || ! empty( $riga['attiva'] ),
			'desktop' => ! empty( $riga['desktop'] ),
			'tablet'  => ! empty( $riga['tablet'] ),
			'mobile'  => ! empty( $riga['mobile'] ),
		);
	}
	return $config;
}

/**
 * Sanifica i valori inviati dalla scheda "Sezioni". Senza la scheda nel modulo restano quelli salvati.
 */
function nl_sezioni_sanifica( $input ) {
	if ( ! is_array( $input ) || empty( $input['_inviato'] ) ) {
		return nl_sezioni_config();
	}
	$out = array();
	foreach ( nl_sezioni_elenco() as $chiave => $sezione ) {
		$riga          = (array) ( $input[ $chiave ] ?? array() );
		$out[ $chiave ] = array(
			'attiva'  => ! empty( $sezione['protetta'] ) || ! empty( $riga['attiva'] ) ? 1 : 0,
			'desktop' => empty( $riga['desktop'] ) ? 0 : 1,
			'tablet'  => empty( $riga['tablet'] ) ? 0 : 1,
			'mobile'  => empty( $riga['mobile'] ) ? 0 : 1,
		);
	}
	return $out;
}

function nl_sezione_attiva( $chiave ) {
	return nl_sezioni_config()[ $chiave ]['attiva'] ?? false;
}

/**
 * Dispositivi su cui la sezione è nascosta (chiavi di nl_dispositivi()). Vuoto se è visibile ovunque.
 */
function nl_sezione_nascosta_su( $chiave ) {
	$riga = nl_sezioni_config()[ $chiave ] ?? null;
	if ( ! $riga ) {
		return array();
	}
	return array_values( array_filter( array_keys( nl_dispositivi() ), fn( $d ) => empty( $riga[ $d ] ) ) );
}

/**
 * Visibile ai visitatori su almeno un dispositivo.
 */
function nl_sezione_visibile( $chiave ) {
	return nl_sezione_attiva( $chiave ) && count( nl_sezione_nascosta_su( $chiave ) ) < count( nl_dispositivi() );
}

/**
 * Anteprima per chi gestisce la landing: sezioni nascoste e disattivate visibili, con etichetta.
 */
function nl_anteprima_sezioni() {
	return is_front_page() && current_user_can( 'manage_options' );
}

/**
 * Etichetta di stato per l'anteprima: "Disattivata", "Nascosta su Mobile e Tablet". Vuota se visibile ovunque.
 */
function nl_sezione_stato( $chiave ) {
	if ( ! nl_sezione_attiva( $chiave ) ) {
		return 'Disattivata';
	}
	$nascosta = nl_sezione_nascosta_su( $chiave );
	if ( ! $nascosta ) {
		return '';
	}
	if ( count( $nascosta ) === count( nl_dispositivi() ) ) {
		return 'Nascosta su tutti i dispositivi';
	}
	$nomi = array_map( fn( $d ) => nl_dispositivi()[ $d ]['nome'], $nascosta );
	return 'Nascosta su ' . implode( ' e ', $nomi );
}

/**
 * Classi che nascondono un elemento sui dispositivi indicati (vedi nl_sezioni_css()).
 */
function nl_classi_nascosta( array $dispositivi ) {
	return implode( ' ', array_map( fn( $d ) => 'nl-no-' . $d, $dispositivi ) );
}

/**
 * Attributi class e data-nl-stato del contenitore di una sezione: <section <?php nl_sezione_attr( 'faq', 'sezione faq' ); ?>>.
 */
function nl_sezione_attr( $chiave, $classi ) {
	$classi = trim( $classi . ' ' . nl_classi_nascosta( nl_sezione_nascosta_su( $chiave ) ) );
	echo 'class="' . esc_attr( $classi ) . '"';
	$stato = nl_sezione_stato( $chiave );
	if ( $stato && nl_anteprima_sezioni() ) {
		echo ' data-nl-stato="' . esc_attr( nl_sezioni_elenco()[ $chiave ]['nome'] . ': ' . $stato ) . '"';
	}
}

/**
 * Stampa una sezione della landing, se va mostrata.
 * Con la sezione nascosta su qualche dispositivo, le sue immagini passano in lazy load:
 * il browser non scarica le immagini lazy dentro un elemento nascosto (display: none).
 */
function nl_sezione( $chiave ) {
	$elenco = nl_sezioni_elenco();
	if ( ! isset( $elenco[ $chiave ] ) || ! empty( $elenco[ $chiave ]['dentro'] ) ) {
		return;
	}
	// Hero e referente contengono altri blocchi (prezzi, Numeri, Metodo): decide il template quali stampare.
	$blocchi = array_merge( array( $chiave ), array_keys( wp_list_filter( $elenco, array( 'dentro' => $chiave ) ) ) );
	if ( ! array_filter( $blocchi, 'nl_sezione_attiva' ) && ! nl_anteprima_sezioni() ) {
		return;
	}

	$file = 'preventivo' === $chiave ? 'preventivo' : $chiave;
	if ( ! nl_sezione_nascosta_su( $chiave ) ) {
		get_template_part( 'template-parts/' . $file );
		return;
	}

	ob_start();
	get_template_part( 'template-parts/' . $file );
	echo nl_immagini_lazy( ob_get_clean() ); // phpcs:ignore WordPress.Security.EscapeOutput -- HTML dei template del tema.
}

/**
 * Tutte le immagini in lazy load, senza priorità alta.
 */
function nl_immagini_lazy( $html ) {
	return preg_replace_callback(
		'/<img\b[^>]*>/i',
		function ( $m ) {
			$img = preg_replace( '/\s(loading|fetchpriority)="[^"]*"/i', '', $m[0] );
			return preg_replace( '/^<img\b/i', '<img loading="lazy"', $img );
		},
		$html
	);
}

/**
 * Link interno a una sezione, che tiene conto della sua visibilità.
 * - Sezione disattivata: il link porta al form.
 * - Sezione nascosta su un dispositivo dove il form è visibile: data-ripiego, e landing.js porta al form.
 * - Destinazione e form entrambi nascosti su un dispositivo: il link si nasconde lì (classi nl-no-*).
 * Restituisce array( href, classi da mettere sul link o sul suo contenitore, attributi extra ).
 */
function nl_link_sezione( $href ) {
	$ancora = 0 === strpos( (string) $href, '#' ) ? substr( $href, 1 ) : '';
	$chiave = '';
	foreach ( nl_sezioni_elenco() as $k => $s ) {
		if ( in_array( $ancora, $s['ancore'] ?? array(), true ) ) {
			$chiave = $k;
			break;
		}
	}
	if ( '' === $chiave ) {
		return array( $href, '', '' );
	}

	if ( ! nl_sezione_attiva( $chiave ) ) {
		$href   = '#preventivo';
		$chiave = 'preventivo';
	}

	$form_nascosto = nl_sezione_nascosta_su( 'preventivo' );
	if ( 'preventivo' === $chiave ) {
		return array( $href, nl_classi_nascosta( $form_nascosto ), '' );
	}

	$nascosta = nl_sezione_nascosta_su( $chiave );
	return array(
		$href,
		nl_classi_nascosta( array_intersect( $nascosta, $form_nascosto ) ),
		array_diff( $nascosta, $form_nascosto ) ? ' data-ripiego="#preventivo"' : '',
	);
}

/**
 * Classi che nascondono un elemento dove il link che contiene non porterebbe a nulla.
 */
function nl_classi_link( $href ) {
	return nl_link_sezione( $href )[1];
}

/**
 * CSS della visibilità per dispositivo, inline nell'head subito dopo il CSS critico (niente sezioni che lampeggiano).
 * In anteprima (da loggato) niente viene nascosto: le sezioni hanno bordo tratteggiato ed etichetta.
 */
function nl_sezioni_css() {
	$css = '';
	if ( nl_anteprima_sezioni() ) {
		$css .= '[data-nl-stato]{position:relative;outline:3px dashed var(--c-cta);outline-offset:-3px}';
		$css .= '[data-nl-stato]::before{content:attr(data-nl-stato);position:absolute;top:.75rem;right:.75rem;z-index:5;padding:.25rem .75rem;border-radius:999px;background:var(--c-scuro);color:#fff;font-size:.8125rem;font-weight:700;line-height:1.4;pointer-events:none}';
		return $css;
	}
	foreach ( nl_dispositivi() as $chiave => $d ) {
		$css .= '@media ' . $d['media'] . '{.nl-no-' . $chiave . '{display:none!important}}';
	}
	return $css;
}

add_action( 'wp_head', function () {
	if ( is_front_page() ) {
		echo '<style id="nl-sezioni">' . nl_sezioni_css() . "</style>\n"; // phpcs:ignore WordPress.Security.EscapeOutput -- CSS generato dal tema.
	}
}, 2 );

/**
 * Sezioni disattivate (chiavi), il cui CSS non va caricato. Vuoto fuori dalla landing e in anteprima.
 */
function nl_sezioni_spente() {
	if ( ! is_front_page() || nl_anteprima_sezioni() ) {
		return array();
	}
	return array_keys( array_filter( nl_sezioni_elenco(), fn( $s, $k ) => ! nl_sezione_attiva( $k ), ARRAY_FILTER_USE_BOTH ) );
}

/**
 * Toglie da un CSS del tema le regole delle sezioni disattivate:
 * stanno tra "/* @sezione chiave *\/" e il marcatore successivo ("@sezione comune" chiude un blocco).
 */
function nl_css_senza_spente( $css ) {
	$spente = nl_sezioni_spente();
	if ( ! $spente ) {
		return $css;
	}
	$parti = preg_split( '#^/\* @sezione (\S+) \*/\R#m', $css, -1, PREG_SPLIT_DELIM_CAPTURE );
	$css   = $parti[0];
	for ( $i = 1; $i < count( $parti ); $i += 2 ) {
		if ( ! in_array( $parti[ $i ], $spente, true ) ) {
			$css .= $parti[ $i + 1 ];
		}
	}
	return $css;
}

/**
 * Foglio sezioni.css della landing senza le regole delle sezioni disattivate.
 * Le regole di una sezione stanno tra "@sezione chiave" e il marcatore successivo in sezioni.css.
 * Il file ridotto viene scritto una volta in wp-content/uploads/nativo-landing/ e riusato finché
 * non cambiano sezioni.css o le sezioni attive. Se non si può scrivere, si usa il file completo.
 */
function nl_sezioni_css_url() {
	$file = NL_DIR . '/assets/css/sezioni.css';
	$ver  = filemtime( $file );
	$url  = add_query_arg( 'ver', $ver, NL_URI . '/assets/css/sezioni.css' );

	$spente = nl_sezioni_spente();
	if ( ! $spente ) {
		return $url;
	}

	$uploads = wp_upload_dir( null, false );
	if ( ! empty( $uploads['error'] ) ) {
		return $url;
	}
	$nome = 'sezioni-' . substr( md5( $ver . '|' . implode( ',', $spente ) ), 0, 12 ) . '.css';
	$dir  = trailingslashit( $uploads['basedir'] ) . 'nativo-landing';
	if ( ! file_exists( "{$dir}/{$nome}" ) ) {
		$css = nl_css_senza_spente( file_get_contents( $file ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions
		// sezioni.css non contiene url(): spostarlo in uploads non rompe percorsi.
		if ( ! wp_mkdir_p( $dir ) || false === file_put_contents( "{$dir}/{$nome}", $css ) ) { // phpcs:ignore WordPress.WP.AlternativeFunctions
			return $url;
		}
		// Le versioni precedenti non servono più.
		foreach ( (array) glob( "{$dir}/sezioni-*.css" ) as $vecchio ) {
			if ( basename( $vecchio ) !== $nome ) {
				wp_delete_file( $vecchio );
			}
		}
	}
	return trailingslashit( $uploads['baseurl'] ) . 'nativo-landing/' . $nome;
}
