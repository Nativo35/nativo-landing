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
	wp_enqueue_script( 'nl-admin', NL_URI . '/assets/js/admin.js', array(), NL_VERSION, true );
	wp_add_inline_style( 'common', '
		.nl-admin .nl-riga{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px;max-width:760px;margin-bottom:12px}
		.nl-admin .nl-testimonianza{background:#fff;border:1px solid #dcdcde;padding:12px 16px;margin-bottom:12px;max-width:760px}
		.nl-admin .nl-testimonianza textarea{width:100%}
		.nl-admin .nl-foto img{display:block;width:120px;height:120px;object-fit:cover;border-radius:50%;margin-bottom:8px}
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
			'nome'     => sanitize_text_field( $t['nome'] ?? '' ),
			'attivita' => sanitize_text_field( $t['attivita'] ?? '' ),
			'testo'    => sanitize_textarea_field( $t['testo'] ?? '' ),
		);
		if ( '' !== $t['nome'] && '' !== $t['testo'] ) {
			$out['testimonianze'][] = $t;
		}
	}

	$out['foto_id'] = absint( $input['foto_id'] ?? 0 );

	foreach ( array( 'telefono', 'whatsapp', 'indirizzo', 'piva', 'rea', 'sdi' ) as $k ) {
		$out[ $k ] = sanitize_text_field( $input[ $k ] ?? $def[ $k ] );
	}
	$out['email'] = sanitize_email( $input['email'] ?? $def['email'] );

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
			<p class="description">La sezione compare ai visitatori solo quando c'è almeno una testimonianza con nome e testo.</p>
			<div id="nl-testimonianze" data-nome="<?php echo esc_attr( $n ); ?>">
				<?php foreach ( nl_opt( 'testimonianze' ) as $i => $t ) : ?>
					<?php nl_admin_testimonianza( $i, $t ); ?>
				<?php endforeach; ?>
			</div>
			<template id="nl-testimonianza-modello"><?php nl_admin_testimonianza( '__i__', array() ); ?></template>
			<p><button type="button" class="button" id="nl-aggiungi-testimonianza">Aggiungi testimonianza</button></p>

			<h2>Foto di Matteo</h2>
			<div class="nl-foto">
				<div id="nl-foto-anteprima"><?php echo $foto_id ? wp_get_attachment_image( $foto_id, 'nl-ritratto' ) : ''; ?></div>
				<input type="hidden" id="nl-foto-id" name="<?php echo esc_attr( $n ); ?>[foto_id]" value="<?php echo esc_attr( $foto_id ?: '' ); ?>">
				<button type="button" class="button" id="nl-foto-scegli">Scegli foto</button>
				<button type="button" class="button-link-delete" id="nl-foto-rimuovi" <?php echo $foto_id ? '' : 'hidden'; ?>>Rimuovi</button>
				<p class="description">Ritratto quadrato, almeno 320 × 320 px.</p>
			</div>

			<h2>Contatti e dati aziendali</h2>
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

			<?php submit_button( 'Salva impostazioni' ); ?>
		</form>
	</div>
	<?php
}

function nl_admin_testimonianza( $i, $t ) {
	$base = NL_OPZIONE . "[testimonianze][{$i}]";
	?>
	<div class="nl-testimonianza">
		<div class="nl-riga">
			<label><span>Nome e cognome</span><input type="text" name="<?php echo esc_attr( $base . '[nome]' ); ?>" value="<?php echo esc_attr( $t['nome'] ?? '' ); ?>"></label>
			<label><span>Attività</span><input type="text" name="<?php echo esc_attr( $base . '[attivita]' ); ?>" value="<?php echo esc_attr( $t['attivita'] ?? '' ); ?>"></label>
		</div>
		<label><span>Testo</span><textarea rows="3" name="<?php echo esc_attr( $base . '[testo]' ); ?>"><?php echo esc_textarea( $t['testo'] ?? '' ); ?></textarea></label>
		<p><button type="button" class="button-link-delete nl-rimuovi-testimonianza">Rimuovi testimonianza</button></p>
	</div>
	<?php
}
