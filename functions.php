<?php
/**
 * Nativo Landing: setup del tema.
 */

defined( 'ABSPATH' ) || exit;

define( 'NL_VERSION', '0.1.0' );
define( 'NL_DIR', get_template_directory() );
define( 'NL_URI', get_template_directory_uri() );

require NL_DIR . '/inc/opzioni.php';
require NL_DIR . '/inc/admin.php';
require NL_DIR . '/inc/icone.php';

add_action( 'after_setup_theme', function () {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'script', 'style', 'gallery', 'caption' ) );
	add_image_size( 'nl-ritratto', 160, 160, true );
} );

/**
 * Pulizia dell'head: niente emoji, block library, stili globali e link superflui.
 */
add_action( 'init', function () {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	remove_action( 'wp_head', 'rest_output_link_wp_head' );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
	remove_action( 'wp_head', 'feed_links', 2 );
	remove_action( 'wp_head', 'feed_links_extra', 3 );
} );

add_action( 'wp_enqueue_scripts', function () {
	foreach ( array( 'wp-block-library', 'wp-block-library-theme', 'classic-theme-styles', 'global-styles' ) as $handle ) {
		wp_dequeue_style( $handle );
	}
}, 100 );

/**
 * Font della prima schermata in preload e CSS critico inline.
 */
add_action( 'wp_head', function () {
	foreach ( array( 'gilroy-400', 'gilroy-600' ) as $font ) {
		printf(
			'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
			esc_url( NL_URI . '/assets/fonts/' . $font . '.woff2' )
		);
	}

	$css = file_get_contents( NL_DIR . '/assets/css/critical.css' );
	$css = str_replace( '../fonts/', NL_URI . '/assets/fonts/', $css );
	echo '<style id="nl-critical">' . $css . "</style>\n"; // phpcs:ignore WordPress.Security.EscapeOutput -- file del tema.
}, 2 );

/**
 * CSS delle sezioni sotto la prima schermata: caricato senza bloccare il rendering.
 */
add_action( 'wp_head', function () {
	$href = esc_url( add_query_arg( 'ver', filemtime( NL_DIR . '/assets/css/sezioni.css' ), NL_URI . '/assets/css/sezioni.css' ) );
	echo '<link rel="stylesheet" href="' . $href . '" media="print" onload="this.media=\'all\'">' . "\n"; // phpcs:ignore WordPress.WP.EnqueuedResources
	echo '<noscript><link rel="stylesheet" href="' . $href . '"></noscript>' . "\n"; // phpcs:ignore WordPress.WP.EnqueuedResources
}, 3 );

add_action( 'wp_head', function () {
	echo '<meta name="description" content="' . esc_attr( 'Siti web su misura per PMI e professionisti in Toscana: un unico referente, prezzi chiari fin da subito, SEO e mobile inclusi. Preventivo gratuito.' ) . '">' . "\n";
	echo '<meta name="theme-color" content="#FFFFFF">' . "\n";
}, 1 );

add_filter( 'body_class', function ( $classes ) {
	$classes[] = 'variante-' . nl_variante();
	return $classes;
} );

add_filter( 'pre_get_document_title', function ( $titolo ) {
	return is_front_page() ? 'Siti web su misura per PMI in Toscana | Nativo Studio' : $titolo;
} );
