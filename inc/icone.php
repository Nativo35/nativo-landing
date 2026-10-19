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
