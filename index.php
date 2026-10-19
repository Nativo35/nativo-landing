<?php
/**
 * Fallback richiesto da WordPress: la landing usa front-page.php.
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<section class="contenitore pagina-semplice">
	<?php
	while ( have_posts() ) :
		the_post();
		the_title( '<h1>', '</h1>' );
		the_content();
	endwhile;
	?>
</section>
<?php
get_footer();
