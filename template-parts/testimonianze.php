<?php
/**
 * Testimonianze da Impostazioni landing.
 * Visitatori: solo quelle pubblicate; se non ce n'è nessuna la sezione è nascosta.
 * Da loggato: anche quelle non pubblicate, con l'etichetta "In attesa di approvazione".
 * Due versioni grafiche, scelte in Impostazioni landing:
 * - card: card a carosello (testimonianze-card.php);
 * - evidenza: una citazione grande alla volta su banda scura (testimonianze-evidenza.php).
 * Da loggato si possono confrontare con ?testimonianze=card o ?testimonianze=evidenza, senza salvare.
 */

defined( 'ABSPATH' ) || exit;

$nl_admin         = current_user_can( 'manage_options' );
$nl_testimonianze = array_values(
	array_filter(
		(array) nl_opt( 'testimonianze' ),
		fn( $t ) => $nl_admin || ! empty( $t['pubblicata'] )
	)
);

if ( ! $nl_testimonianze ) {
	if ( $nl_admin ) {
		echo '<div class="contenitore sezione-spenta">' . nl_segnaposto_admin( 'Testimonianze nascoste: aggiungine una e attiva "Pubblicata" in Impostazioni landing' ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput -- HTML già sanificato.
	}
	return;
}

$nl_stile = nl_opt( 'stile_testimonianze' );
if ( $nl_admin && isset( $_GET['testimonianze'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification -- sola anteprima per amministratori.
	$nl_stile = sanitize_key( wp_unslash( $_GET['testimonianze'] ) ); // phpcs:ignore WordPress.Security.NonceVerification
}
$nl_stile = in_array( $nl_stile, array( 'card', 'evidenza' ), true ) ? $nl_stile : 'card';

get_template_part( 'template-parts/testimonianze-' . $nl_stile, null, array( 'testimonianze' => $nl_testimonianze ) );
