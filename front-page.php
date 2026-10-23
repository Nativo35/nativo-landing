<?php
/**
 * Landing: le sezioni sono in template-parts/, nell'ordine approvato in analisi.md (elenco in inc/sezioni.php).
 */

defined( 'ABSPATH' ) || exit;

get_header();

// Ordine, attivazione e visibilità per dispositivo da Impostazioni landing › Sezioni (inc/sezioni.php).
foreach ( array_keys( nl_sezioni_elenco() ) as $nl_chiave ) {
	nl_sezione( $nl_chiave );
}

get_footer();
