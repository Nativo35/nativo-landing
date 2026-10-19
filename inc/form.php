<?php
/**
 * Contact Form 7: script solo dove c'è il form, niente CSS del plugin, niente <p> automatici.
 * Pagina /grazie/ esclusa dai motori di ricerca.
 */

defined( 'ABSPATH' ) || exit;

add_filter( 'wpcf7_load_js', '__return_false' );
add_filter( 'wpcf7_load_css', '__return_false' );
add_filter( 'wpcf7_autop_or_not', '__return_false' );

add_filter( 'wp_robots', function ( $robots ) {
	if ( is_page( 'grazie' ) ) {
		$robots['noindex']  = true;
		$robots['nofollow'] = true;
	}
	return $robots;
} );
