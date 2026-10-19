<?php
/**
 * Icone SVG inline, tratto leggero, colore ereditato dal testo.
 */

defined( 'ABSPATH' ) || exit;

function nl_icona( $nome, $classe = 'icona' ) {
	$tracciati = array(
		'telefono' => '<path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2"/>',
		'spunta'   => '<path d="m5 12 5 5L20 7"/>',
		'freccia'  => '<path d="M5 12h14M13 6l6 6-6 6"/>',
		'whatsapp' => '<path d="M3.5 20.5 4.9 16.4A8.5 8.5 0 1 1 8 19.3z"/><path d="M9 8.6c0 3.4 2.9 6.4 6.3 6.4l1-1.6-2-1-1 .9a4.6 4.6 0 0 1-2.6-2.6l.9-1-1-2z"/>',
		'lento'      => '<circle cx="12" cy="13" r="8"/><path d="M12 9v4l2.5 2.5M9.5 2h5"/>',
		'smartphone' => '<rect x="6" y="2.5" width="12" height="19" rx="2.5"/><path d="M11 18h2"/>',
		'ricerca'    => '<circle cx="10.5" cy="10.5" r="6.5"/><path d="m15.5 15.5 5 5"/>',
		'posta'      => '<path d="M3 13h5l1.5 3h5L16 13h5"/><path d="M5.5 5h13L21 13v5a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-5z"/>',
	);
	if ( ! isset( $tracciati[ $nome ] ) ) {
		return;
	}
	printf(
		'<svg class="%s" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">%s</svg>',
		esc_attr( $classe ),
		$tracciati[ $nome ] // phpcs:ignore WordPress.Security.EscapeOutput -- tracciati fissi nel codice.
	);
}
