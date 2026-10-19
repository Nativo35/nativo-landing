<?php
/**
 * Header essenziale: logo, telefono e CTA. Nessun menu, per non disperdere il traffico delle campagne.
 */

defined( 'ABSPATH' ) || exit;
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="salta-contenuto" href="#contenuto">Vai al contenuto</a>

<header class="testata">
	<div class="contenitore testata__barra">
		<a class="testata__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="Nativo Studio, torna all'inizio">
			<img src="<?php echo esc_url( NL_URI . '/assets/img/logo-nativo-studio.webp' ); ?>" width="148" height="30" alt="Nativo Studio" fetchpriority="high">
		</a>
		<!-- DA VERIFICARE: logo in formato SVG (ora è un WebP ricavato dal PNG del sito). -->
		<div class="testata__azioni">
			<a class="testata__tel" href="<?php echo esc_attr( nl_tel_href() ); ?>" data-evento="clic_telefono" aria-label="Chiama Nativo Studio al <?php echo esc_attr( nl_tel_breve() ); ?>">
				<?php nl_icona( 'telefono' ); ?>
				<span class="testata__tel-numero"><?php echo esc_html( nl_tel_breve() ); ?></span>
			</a>
			<a class="pulsante pulsante--piccolo" href="<?php echo esc_url( is_front_page() ? '#preventivo' : home_url( '/#preventivo' ) ); ?>" data-cta="header">Preventivo gratis</a>
		</div>
	</div>
</header>

<main id="contenuto">
