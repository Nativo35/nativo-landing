<?php
/**
 * Pagina "Impostazioni landing" nel menu di WordPress (Settings API, senza plugin).
 */

defined( 'ABSPATH' ) || exit;

add_action( 'admin_menu', function () {
	add_menu_page(
		'Impostazioni landing',
		'Impostazioni landing',
		'manage_options',
		'nl-impostazioni',
		'nl_admin_pagina',
		'dashicons-megaphone',
		3
	);
} );

add_action( 'admin_init', function () {
	register_setting(
		'nl_impostazioni_gruppo',
		NL_OPZIONE,
		array(
			'type'              => 'array',
			'sanitize_callback' => 'nl_admin_sanifica',
			'default'           => nl_default(),
		)
	);
} );

add_action( 'admin_enqueue_scripts', function ( $hook ) {
	if ( 'toplevel_page_nl-impostazioni' !== $hook ) {
		return;
	}
	wp_enqueue_media();
	wp_enqueue_script( 'nl-admin', NL_URI . '/assets/js/admin.js', array(), filemtime( NL_DIR . '/assets/js/admin.js' ), true );
	wp_add_inline_style( 'common', '
		.nl-admin .nl-riga{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px;max-width:760px;margin-bottom:12px}
		.nl-admin .nl-voce{background:#fff;border:1px solid #dcdcde;padding:12px 16px;margin-bottom:12px;max-width:760px}
		.nl-admin .nl-voce textarea{width:100%}
		.nl-admin .nl-media__anteprima img{display:block;width:160px;height:auto;margin-bottom:8px;border:1px solid #dcdcde}
		.nl-admin .nl-media--ritratto .nl-media__anteprima img{width:120px;height:120px;object-fit:cover;border-radius:50%}
		.nl-admin label span{display:block;font-weight:600;margin-bottom:4px}
	' );
} );

function nl_admin_sanifica( $input ) {
	$def = nl_default();
	$out = array();

	foreach ( array( 'prezzo_vetrina', 'prezzo_blog', 'prezzo_ecommerce' ) as $k ) {
		$out[ $k ] = isset( $input[ $k ] ) && '' !== $input[ $k ] ? absint( $input[ $k ] ) : 0;
	}

	$out['sconto_attivo']      = empty( $input['sconto_attivo'] ) ? 0 : 1;
	$out['sconto_percentuale'] = min( 100, absint( $input['sconto_percentuale'] ?? 0 ) );
	$out['sconto_giorni']      = absint( $input['sconto_giorni'] ?? 0 );

	$out['numeri'] = array();
	foreach ( $def['numeri'] as $i => $numero_def ) {
		$riga            = $input['numeri'][ $i ] ?? array();
		$valore          = trim( (string) ( $riga['valore'] ?? '' ) );
		$out['numeri'][] = array(
			'valore'    => '' === $valore ? '' : (string) absint( $valore ),
			'suffisso'  => sanitize_text_field( $riga['suffisso'] ?? '' ),
			'etichetta' => sanitize_text_field( $riga['etichetta'] ?? $numero_def['etichetta'] ),
		);
	}

	$out['testimonianze'] = array();
	foreach ( (array) ( $input['testimonianze'] ?? array() ) as $t ) {
		$t = array(
			'nome'       => sanitize_text_field( $t['nome'] ?? '' ),
			'ruolo'      => sanitize_text_field( $t['ruolo'] ?? '' ),
			'azienda'    => sanitize_text_field( $t['azienda'] ?? ( $t['attivita'] ?? '' ) ),
			'testo'      => sanitize_textarea_field( $t['testo'] ?? '' ),
			'immagine'   => absint( $t['immagine'] ?? 0 ),
			'pubblicata' => empty( $t['pubblicata'] ) ? 0 : 1,
		);
		if ( '' !== $t['nome'] && '' !== $t['testo'] ) {
			$out['testimonianze'][] = $t;
		}
	}

	$out['progetti_attivi'] = empty( $input['progetti_attivi'] ) ? 0 : 1;
	$out['territorio_attivo'] = empty( $input['territorio_attivo'] ) ? 0 : 1;
	$out['stile_testimonianze'] = in_array( $input['stile_testimonianze'] ?? '', array( 'card', 'evidenza' ), true ) ? $input['stile_testimonianze'] : 'card';

	$out['progetti'] = array();
	foreach ( (array) ( $input['progetti'] ?? array() ) as $pr ) {
		$pr = array(
			'nome'     => sanitize_text_field( $pr['nome'] ?? '' ),
			'tipo'     => sanitize_text_field( $pr['tipo'] ?? '' ),
			'contesto' => sanitize_textarea_field( $pr['contesto'] ?? '' ),
			'immagine' => absint( $pr['immagine'] ?? 0 ),
		);
		if ( '' !== $pr['nome'] ) {
			$out['progetti'][] = $pr;
		}
	}

	$out['foto_id'] = absint( $input['foto_id'] ?? 0 );

	foreach ( array( 'telefono', 'whatsapp', 'indirizzo', 'piva', 'rea', 'sdi', 'referente_nome', 'referente_ruolo', 'azienda_nome' ) as $k ) {
		$out[ $k ] = sanitize_text_field( $input[ $k ] ?? $def[ $k ] );
	}
	$out['email'] = sanitize_email( $input['email'] ?? $def['email'] );
	$out['sito_web'] = esc_url_raw( $input['sito_web'] ?? $def['sito_web'] );
	$out['gtm_id'] = preg_replace( '/[^A-Z0-9-]/', '', strtoupper( (string) ( $input['gtm_id'] ?? '' ) ) );

	return $out;
}

function nl_admin_campo( $chiave, $etichetta, $tipo = 'text', $attr = '' ) {
	printf(
		'<label><span>%1$s</span><input type="%2$s" name="%3$s[%4$s]" value="%5$s" class="regular-text" %6$s></label>',
		esc_html( $etichetta ),
		esc_attr( $tipo ),
		esc_attr( NL_OPZIONE ),
		esc_attr( $chiave ),
		esc_attr( nl_opt( $chiave ) ),
		$attr // phpcs:ignore WordPress.Security.EscapeOutput -- attributi fissi scritti nel codice.
	);
}

function nl_admin_pagina() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$n       = NL_OPZIONE;
	$foto_id = (int) nl_opt( 'foto_id' );
	?>
	<div class="wrap nl-admin">
		<h1>Impostazioni landing</h1>
		<p>Questi valori compaiono in tutta la landing. Ogni modifica vale ovunque, così i testi restano coerenti.</p>

		<form method="post" action="options.php">
			<?php settings_fields( 'nl_impostazioni_gruppo' ); ?>

			<h2>Prezzi "a partire da" (€)</h2>
			<div class="nl-riga">
				<?php
				nl_admin_campo( 'prezzo_vetrina', 'Sito Vetrina', 'number', 'min="0" step="1"' );
				nl_admin_campo( 'prezzo_blog', 'Sito + Blog', 'number', 'min="0" step="1"' );
				nl_admin_campo( 'prezzo_ecommerce', 'E-commerce', 'number', 'min="0" step="1"' );
				?>
			</div>

			<h2>Sconto</h2>
			<p><label><input type="checkbox" name="<?php echo esc_attr( $n ); ?>[sconto_attivo]" value="1" <?php checked( nl_opt( 'sconto_attivo' ) ); ?>> Sconto attivo</label></p>
			<div class="nl-riga">
				<?php
				nl_admin_campo( 'sconto_percentuale', 'Percentuale (%)', 'number', 'min="0" max="100" step="1"' );
				nl_admin_campo( 'sconto_giorni', 'Giorni di validità dal preventivo', 'number', 'min="0" step="1"' );
				?>
			</div>
			<p class="description">Anteprima: <?php echo nl_sconto_testo() ? wp_kses_post( nl_sconto_testo() ) : 'sconto non visibile.'; ?></p>

			<h2>Numeri</h2>
			<p class="description">Un numero lasciato vuoto non viene mostrato ai visitatori. Da loggato vedi un segnaposto.</p>
			<?php foreach ( nl_opt( 'numeri' ) as $i => $numero ) : ?>
				<div class="nl-riga">
					<label><span>Valore</span><input type="number" min="0" name="<?php echo esc_attr( "{$n}[numeri][{$i}][valore]" ); ?>" value="<?php echo esc_attr( $numero['valore'] ); ?>"></label>
					<label><span>Suffisso (+, %…)</span><input type="text" name="<?php echo esc_attr( "{$n}[numeri][{$i}][suffisso]" ); ?>" value="<?php echo esc_attr( $numero['suffisso'] ); ?>"></label>
					<label><span>Etichetta</span><input type="text" name="<?php echo esc_attr( "{$n}[numeri][{$i}][etichetta]" ); ?>" value="<?php echo esc_attr( $numero['etichetta'] ); ?>"></label>
				</div>
			<?php endforeach; ?>

			<h2>Testimonianze</h2>
			<p class="description">Ai visitatori compaiono solo le testimonianze con "Pubblicata" attivo; se non ce n'è nessuna la sezione resta nascosta. Da loggato vedi anche le altre, con l'etichetta "In attesa di approvazione". Senza foto o logo compare un cerchio con le iniziali.</p>
			<p><strong>Versione grafica</strong></p>
			<p>
				<label><input type="radio" name="<?php echo esc_attr( $n ); ?>[stile_testimonianze]" value="card" <?php checked( nl_opt( 'stile_testimonianze' ), 'card' ); ?>> Card a carosello</label><br>
				<label><input type="radio" name="<?php echo esc_attr( $n ); ?>[stile_testimonianze]" value="evidenza" <?php checked( nl_opt( 'stile_testimonianze' ), 'evidenza' ); ?>> Citazione in evidenza (una alla volta, su banda scura)</label>
			</p>
			<p class="description">Per confrontarle senza salvare, da loggato apri la landing con <code>?testimonianze=card</code> oppure <code>?testimonianze=evidenza</code>.</p>
			<?php nl_admin_ripetitore( 'testimonianze', 'nl_admin_testimonianza', 'Aggiungi testimonianza' ); ?>

			<h2>Progetti realizzati</h2>
			<p><label><input type="checkbox" name="<?php echo esc_attr( $n ); ?>[progetti_attivi]" value="1" <?php checked( nl_opt( 'progetti_attivi' ) ); ?>> Mostra la sezione "Progetti veri, per attività come la tua"</label></p>
			<p class="description">Ai visitatori compaiono solo i progetti con un'immagine. Da loggato vedi anche gli altri, con i segnaposto. Immagine orizzontale, almeno 1200 × 750 px.</p>
			<?php nl_admin_ripetitore( 'progetti', 'nl_admin_progetto', 'Aggiungi progetto' ); ?>

			<h2>Cartina della Toscana</h2>
			<p><label><input type="checkbox" name="<?php echo esc_attr( $n ); ?>[territorio_attivo]" value="1" <?php checked( nl_opt( 'territorio_attivo' ) ); ?>> Mostra la sezione "Lavoriamo in tutta la Toscana"</label></p>

			<h2>Foto di Matteo</h2>
			<?php nl_admin_media( "{$n}[foto_id]", $foto_id, 'Scegli foto', 'nl-ritratto', 'nl-media--ritratto' ); ?>
			<p class="description">Ritratto quadrato, almeno 320 × 320 px.</p>

			<h2>Contatti e dati aziendali</h2>
			<p class="description">Questi dati compongono anche il file "Salva il contatto" (vCard): se cambi un recapito, il file si aggiorna da solo.</p>
			<div class="nl-riga">
				<?php
				nl_admin_campo( 'referente_nome', 'Nome del referente' );
				nl_admin_campo( 'referente_ruolo', 'Ruolo' );
				nl_admin_campo( 'azienda_nome', 'Azienda' );
				?>
			</div>
			<div class="nl-riga">
				<?php nl_admin_campo( 'sito_web', 'Sito web', 'url' ); ?>
			</div>
			<div class="nl-riga">
				<?php
				nl_admin_campo( 'telefono', 'Telefono' );
				nl_admin_campo( 'whatsapp', 'WhatsApp' );
				nl_admin_campo( 'email', 'Email', 'email' );
				?>
			</div>
			<div class="nl-riga">
				<?php
				nl_admin_campo( 'indirizzo', 'Indirizzo' );
				nl_admin_campo( 'piva', 'Partita IVA' );
				nl_admin_campo( 'rea', 'REA' );
				?>
			</div>
			<div class="nl-riga">
				<?php nl_admin_campo( 'sdi', 'Codice SDI' ); ?>
			</div>

			<h2>Tracciamento</h2>
			<div class="nl-riga">
				<?php nl_admin_campo( 'gtm_id', 'ID di Google Tag Manager', 'text', 'placeholder="GTM-XXXXXXX"' ); ?>
			</div>
			<p class="description">GTM parte solo dopo il consenso a statistiche o marketing nel banner di Complianz. Lascia vuoto per spegnerlo.</p>

			<?php submit_button( 'Salva impostazioni' ); ?>
		</form>
	</div>
	<?php
}

/**
 * Elenco ripetibile: voci esistenti, modello per le nuove e pulsante "Aggiungi".
 */
function nl_admin_ripetitore( $chiave, $render, $etichetta ) {
	?>
	<div data-ripetitore="<?php echo esc_attr( $chiave ); ?>">
		<?php foreach ( (array) nl_opt( $chiave ) as $i => $voce ) : ?>
			<?php $render( $i, $voce ); ?>
		<?php endforeach; ?>
	</div>
	<template data-modello="<?php echo esc_attr( $chiave ); ?>"><?php $render( '__i__', array() ); ?></template>
	<p><button type="button" class="button" data-aggiungi="<?php echo esc_attr( $chiave ); ?>"><?php echo esc_html( $etichetta ); ?></button></p>
	<?php
}

/**
 * Campo immagine dalla libreria media (salva l'ID dell'allegato).
 */
function nl_admin_media( $nome, $id, $etichetta, $dimensione = 'medium', $classe = '' ) {
	$id = (int) $id;
	?>
	<div class="nl-media <?php echo esc_attr( $classe ); ?>">
		<div class="nl-media__anteprima"><?php echo $id ? wp_get_attachment_image( $id, $dimensione ) : ''; ?></div>
		<input type="hidden" class="nl-media__id" name="<?php echo esc_attr( $nome ); ?>" value="<?php echo esc_attr( $id ?: '' ); ?>">
		<button type="button" class="button nl-media__scegli"><?php echo esc_html( $etichetta ); ?></button>
		<button type="button" class="button-link-delete nl-media__rimuovi" <?php echo $id ? '' : 'hidden'; ?>>Rimuovi</button>
	</div>
	<?php
}

function nl_admin_testimonianza( $i, $t ) {
	$base = NL_OPZIONE . "[testimonianze][{$i}]";
	?>
	<div class="nl-voce">
		<p><label><input type="checkbox" name="<?php echo esc_attr( $base . '[pubblicata]' ); ?>" value="1" <?php checked( ! empty( $t['pubblicata'] ) ); ?>> <strong>Pubblicata</strong> (visibile ai visitatori)</label></p>
		<div class="nl-riga">
			<label><span>Nome e cognome</span><input type="text" name="<?php echo esc_attr( $base . '[nome]' ); ?>" value="<?php echo esc_attr( $t['nome'] ?? '' ); ?>"></label>
			<label><span>Ruolo</span><input type="text" name="<?php echo esc_attr( $base . '[ruolo]' ); ?>" value="<?php echo esc_attr( $t['ruolo'] ?? '' ); ?>" placeholder="Es. Co-Founder"></label>
			<label><span>Azienda</span><input type="text" name="<?php echo esc_attr( $base . '[azienda]' ); ?>" value="<?php echo esc_attr( $t['azienda'] ?? '' ); ?>"></label>
		</div>
		<label><span>Testo</span><textarea rows="4" name="<?php echo esc_attr( $base . '[testo]' ); ?>"><?php echo esc_textarea( $t['testo'] ?? '' ); ?></textarea></label>
		<p><?php nl_admin_media( $base . '[immagine]', $t['immagine'] ?? 0, 'Scegli foto o logo (facoltativo)', 'nl-ritratto', 'nl-media--ritratto' ); ?></p>
		<p><button type="button" class="button-link-delete nl-rimuovi-voce">Rimuovi testimonianza</button></p>
	</div>
	<?php
}

function nl_admin_progetto( $i, $pr ) {
	$base = NL_OPZIONE . "[progetti][{$i}]";
	?>
	<div class="nl-voce">
		<div class="nl-riga">
			<label><span>Nome</span><input type="text" name="<?php echo esc_attr( $base . '[nome]' ); ?>" value="<?php echo esc_attr( $pr['nome'] ?? '' ); ?>"></label>
			<label><span>Tipo di sito</span><input type="text" name="<?php echo esc_attr( $base . '[tipo]' ); ?>" value="<?php echo esc_attr( $pr['tipo'] ?? '' ); ?>" placeholder="Es. Sito vetrina"></label>
		</div>
		<label><span>Contesto (una frase: settore, città, obiettivo)</span><textarea rows="2" name="<?php echo esc_attr( $base . '[contesto]' ); ?>"><?php echo esc_textarea( $pr['contesto'] ?? '' ); ?></textarea></label>
		<p><?php nl_admin_media( $base . '[immagine]', $pr['immagine'] ?? 0, 'Scegli immagine' ); ?></p>
		<p><button type="button" class="button-link-delete nl-rimuovi-voce">Rimuovi progetto</button></p>
	</div>
	<?php
}
