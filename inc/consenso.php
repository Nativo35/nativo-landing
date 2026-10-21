<?php
/**
 * Consenso cookie (Complianz), Google Tag Manager e pixel di OpenAI.
 *
 * - All'apertura: Consent Mode v2 con tutto negato, nessuno script di Google.
 * - GTM viene caricato solo dopo il consenso a statistiche o marketing (evento cmplz_fire_categories di Complianz).
 * - Ogni cambio di consenso aggiorna i segnali di Consent Mode, letti dai tag dentro GTM.
 * - Il pixel di OpenAI (ChatGPT Ads) parte solo con il consenso marketing.
 * ID di GTM e Pixel ID si impostano in Impostazioni landing; vuoti = spenti.
 */

defined( 'ABSPATH' ) || exit;

// Lo script qui sotto gestisce da sé il consenso: Complianz non deve bloccarlo.
add_filter( 'cmplz_whitelisted_script_tags', function ( $tag ) {
	$tag[] = 'nl-consenso';
	return $tag;
} );

add_action( 'wp_head', function () {
	$gtm   = preg_replace( '/[^A-Z0-9-]/', '', strtoupper( (string) nl_opt( 'gtm_id' ) ) );
	$pixel = preg_replace( '/[^A-Za-z0-9_-]/', '', (string) nl_opt( 'openai_pixel_id' ) );
	if ( ! $gtm && ! $pixel ) {
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
	var gtmId = '<?php echo esc_js( $gtm ); ?>';
	var pixelId = '<?php echo esc_js( $pixel ); ?>';
	var pixelCaricato = false;
	// Pixel di OpenAI (ChatGPT Ads): solo con consenso marketing; legge oppref dall'URL e lo salva nel cookie __oppref.
	function caricaPixel() {
		if ( pixelCaricato || ! pixelId ) { return; }
		pixelCaricato = true;
		( function ( w, d, s, u ) {
			if ( w.oaiq ) { return; }
			var q = function () { q.q.push( arguments ); };
			q.q = [];
			w.oaiq = q;
			var js = d.createElement( s );
			js.async = true;
			js.src = u;
			d.head.appendChild( js );
		} )( window, document, 'script', 'https://bzrcdn.openai.com/sdk/oaiq.min.js' );
		oaiq( 'init', { pixelId: pixelId<?php echo nl_opt( 'openai_debug' ) ? ', debug: true' : ''; ?> } );
	}
	function caricaGTM() {
		if ( caricato || ! gtmId ) { return; }
		caricato = true;
		dataLayer.push( { 'gtm.start': Date.now(), event: 'gtm.js' } );
		var s = document.createElement( 'script' );
		s.async = true;
		s.src = 'https://www.googletagmanager.com/' + 'gtm.js?id=' + gtmId;
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
		window.nlConsensoMarketing = marketing; // letto dal form (campo consenso_marketing)
		document.dispatchEvent( new CustomEvent( 'nl_consenso', { detail: { marketing: marketing } } ) );
		if ( statistiche || marketing ) { caricaGTM(); }
		if ( marketing ) { caricaPixel(); }
		if ( window.oaiq ) { oaiq( 'consent', marketing ); }
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
