<?php
/**
 * Consenso cookie (Complianz) e Google Tag Manager.
 *
 * - All'apertura: Consent Mode v2 con tutto negato, nessuno script di Google.
 * - GTM viene caricato solo dopo il consenso a statistiche o marketing (evento cmplz_fire_categories di Complianz).
 * - Ogni cambio di consenso aggiorna i segnali di Consent Mode, letti dai tag dentro GTM.
 * L'ID del contenitore si imposta in Impostazioni landing; vuoto = GTM spento.
 */

defined( 'ABSPATH' ) || exit;

// Lo script qui sotto gestisce da sé il consenso: Complianz non deve bloccarlo.
add_filter( 'cmplz_whitelisted_script_tags', function ( $tag ) {
	$tag[] = 'nl-consenso';
	return $tag;
} );

add_action( 'wp_head', function () {
	$gtm = preg_replace( '/[^A-Z0-9-]/', '', strtoupper( (string) nl_opt( 'gtm_id' ) ) );
	if ( ! $gtm ) {
		return;
	}
	?>
<script id="nl-consenso">
window.dataLayer = window.dataLayer || [];
function gtag() { dataLayer.push( arguments ); }
gtag( 'consent', 'default', { ad_storage: 'denied', ad_user_data: 'denied', ad_personalization: 'denied', analytics_storage: 'denied', functionality_storage: 'granted', security_storage: 'granted', wait_for_update: 500 } );
gtag( 'set', 'ads_data_redaction', true );
( function () {
	var caricato = false;
	function caricaGTM() {
		if ( caricato ) { return; }
		caricato = true;
		dataLayer.push( { 'gtm.start': Date.now(), event: 'gtm.js' } );
		var s = document.createElement( 'script' );
		s.async = true;
		s.src = 'https://www.googletagmanager.com/' + 'gtm.js?id=<?php echo esc_js( $gtm ); ?>';
		document.head.appendChild( s );
	}
	function applica( categorie ) {
		var statistiche = categorie.indexOf( 'statistics' ) > -1;
		var marketing = categorie.indexOf( 'marketing' ) > -1;
		gtag( 'consent', 'update', {
			analytics_storage: statistiche ? 'granted' : 'denied',
			ad_storage: marketing ? 'granted' : 'denied',
			ad_user_data: marketing ? 'granted' : 'denied',
			ad_personalization: marketing ? 'granted' : 'denied'
		} );
		dataLayer.push( { event: 'consenso_aggiornato', consenso_statistiche: statistiche, consenso_marketing: marketing } );
		if ( statistiche || marketing ) { caricaGTM(); }
	}
	document.addEventListener( 'cmplz_fire_categories', function ( e ) {
		applica( ( e.detail && e.detail.categories ) || [] );
	} );
	document.addEventListener( 'cmplz_revoke', function () {
		applica( [] );
	} );
}() );
</script>
	<?php
}, 1 );
