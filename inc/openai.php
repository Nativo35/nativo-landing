<?php
/**
 * OpenAI Ads (ChatGPT Ads): evento lead_created inviato dal server tramite la Conversions API.
 * Documentazione: https://developers.openai.com/ads/conversions-api
 *
 * - Parte quando Contact Form 7 ha inviato il form "Preventivo landing".
 * - Solo con Pixel ID impostato, chiave presente e consenso marketing del visitatore.
 * - La chiave sta SOLO nella costante NATIVO_OPENAI_CAPI_KEY in wp-config.php: mai nel tema o nella pagina.
 * - id dell'evento = event_id usato dal pixel nel browser (campo nascosto del form): OpenAI conta il lead una volta sola.
 */

defined( 'ABSPATH' ) || exit;

const NL_OPENAI_ENDPOINT = 'https://bzr.openai.com/v1/events';

/**
 * Evento lead_created. Nessun dato personale: solo attribuzione (oppref) e contesto della pagina.
 */
function nl_openai_evento_lead( $event_id, $oppref, $source_url, $timestamp_ms = null ) {
	$evento = array(
		'id'            => (string) $event_id,
		'type'          => 'lead_created',
		'timestamp_ms'  => $timestamp_ms ?? (int) round( microtime( true ) * 1000 ),
		'action_source' => 'web',
		'source_url'    => (string) $source_url,
		'data'          => array( 'type' => 'customer_action' ),
	);
	if ( '' !== (string) $oppref ) {
		$evento['oppref'] = (string) $oppref; // stringa originale, senza modifiche
	}
	return $evento;
}

/**
 * Invia uno o più eventi alla Conversions API.
 * Restituisce null se mancano chiave o Pixel ID (invio saltato senza errori), altrimenti codice e corpo della risposta.
 */
function nl_openai_invia( array $eventi, $validate_only = false, $attendi = true ) {
	$pixel = (string) nl_opt( 'openai_pixel_id' );
	if ( ! defined( 'NATIVO_OPENAI_CAPI_KEY' ) || '' === (string) NATIVO_OPENAI_CAPI_KEY || '' === $pixel ) {
		return null;
	}

	$corpo = array(
		'validate_only'      => (bool) $validate_only,
		'integration_source' => 'nativo-landing-wp',
		'events'             => array_values( $eventi ),
	);

	$risposta = wp_remote_post(
		add_query_arg( 'pid', rawurlencode( $pixel ), NL_OPENAI_ENDPOINT ),
		array(
			'timeout'  => 5,
			'blocking' => $attendi, // in produzione non si aspetta: chi compila il form non deve rallentare
			'headers'  => array(
				'Authorization' => 'Bearer ' . NATIVO_OPENAI_CAPI_KEY,
				'Content-Type'  => 'application/json',
			),
			'body'     => wp_json_encode( $corpo ),
		)
	);

	$esito = is_wp_error( $risposta )
		? array( 'errore' => $risposta->get_error_message() )
		: array(
			'codice' => wp_remote_retrieve_response_code( $risposta ),
			'corpo'  => json_decode( wp_remote_retrieve_body( $risposta ), true ) ?? wp_remote_retrieve_body( $risposta ),
		);

	// L'invio reale si attiva solo dopo una verifica (validate_only) andata a buon fine.
	if ( $validate_only && ! empty( $esito['codice'] ) ) {
		if ( $esito['codice'] >= 200 && $esito['codice'] < 300 ) {
			update_option( 'nl_openai_capi_verificata', wp_date( 'Y-m-d H:i:s' ), false );
		} else {
			delete_option( 'nl_openai_capi_verificata' );
		}
	}

	// In debug si conserva l'ultima risposta (mai la chiave) per mostrarla nel pannello.
	if ( nl_opt( 'openai_debug' ) ) {
		update_option(
			'nl_openai_ultima_risposta',
			array(
				'quando'        => wp_date( 'Y-m-d H:i:s' ),
				'validate_only' => (bool) $validate_only,
				'eventi'        => $corpo['events'],
				'risposta'      => $esito,
			),
			false
		);
	}
	return $esito;
}

/**
 * Dopo l'invio riuscito del form di preventivo: lead_created dal server.
 */
add_action( 'wpcf7_mail_sent', function ( $form ) {
	if ( 'Preventivo landing' !== $form->title() || ! class_exists( 'WPCF7_Submission' ) ) {
		return;
	}
	$invio = WPCF7_Submission::get_instance();
	if ( ! $invio ) {
		return;
	}
	$dati = $invio->get_posted_data();

	// Invio reale solo dopo una verifica riuscita con validate_only (vedi nl_openai_verifica()).
	if ( ! get_option( 'nl_openai_capi_verificata' ) ) {
		return;
	}

	// Senza consenso marketing nel banner nessun dato va alla piattaforma pubblicitaria.
	if ( '1' !== (string) ( $dati['consenso_marketing'] ?? '' ) ) {
		return;
	}

	$event_id = sanitize_text_field( $dati['event_id'] ?? '' );
	if ( ! preg_match( '/^[A-Za-z0-9_-]{8,64}$/', $event_id ) ) {
		$event_id = wp_generate_uuid4(); // senza id del browser non c'è deduplicazione, ma il lead resta
	}

	$evento = nl_openai_evento_lead(
		$event_id,
		sanitize_text_field( $dati['oppref'] ?? '' ),
		esc_url_raw( $invio->get_meta( 'url' ) ?: home_url( '/' ) ),
		(int) round( ( $invio->get_meta( 'timestamp' ) ?: microtime( true ) ) * 1000 )
	);

	// In debug si aspetta la risposta, per poterla leggere nel pannello.
	nl_openai_invia( array( $evento ), false, (bool) nl_opt( 'openai_debug' ) );
} );

/**
 * Verifica la configurazione con un lead_created di prova in validate_only: OpenAI lo controlla senza salvarlo.
 * Se riesce, l'invio reale dal form si attiva.
 */
function nl_openai_verifica() {
	return nl_openai_invia(
		array( nl_openai_evento_lead( 'verifica-' . wp_generate_uuid4(), '', home_url( '/' ) ) ),
		true,
		true
	);
}

// Pulsante "Verifica di nuovo" nel pannello.
add_action( 'admin_post_nl_openai_verifica', function () {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Permessi insufficienti.' );
	}
	check_admin_referer( 'nl_openai_verifica' );
	$esito = nl_openai_verifica();
	set_transient( 'nl_openai_esito_verifica', $esito, 60 );
	wp_safe_redirect( admin_url( 'admin.php?page=nl-impostazioni#tracciamento' ) );
	exit;
} );
