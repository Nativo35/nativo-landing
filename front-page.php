<?php
/**
 * Landing: le sezioni sono in template-parts/, nell'ordine approvato in analisi.md.
 */

defined( 'ABSPATH' ) || exit;

get_header();

get_template_part( 'template-parts/hero' );
get_template_part( 'template-parts/problemi' );
get_template_part( 'template-parts/soluzione' );
get_template_part( 'template-parts/pacchetti' );
get_template_part( 'template-parts/progetti' );
get_template_part( 'template-parts/referente' );

get_footer();
