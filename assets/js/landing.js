/* Landing: barra CTA fissa su mobile. Compare dopo la hero, sparisce quando il form è a schermo. */
( function () {
	const barra = document.getElementById( 'barra-cta' );
	const hero = document.querySelector( '.hero' );
	if ( ! barra || ! hero || ! ( 'IntersectionObserver' in window ) ) {
		return;
	}

	let heroVisibile = true;
	let formVisibile = false;

	const aggiorna = () => {
		const mostra = ! heroVisibile && ! formVisibile;
		barra.classList.toggle( 'is-visibile', mostra );
		barra.toggleAttribute( 'inert', ! mostra );
		barra.setAttribute( 'aria-hidden', String( ! mostra ) );
	};

	// La hero conta come "passata" quando il suo fondo scorre sotto l'header fisso.
	const altezzaHeader = document.querySelector( '.testata' )?.offsetHeight || 0;
	new IntersectionObserver( ( [ voce ] ) => {
		heroVisibile = voce.isIntersecting;
		aggiorna();
	}, { rootMargin: `-${ altezzaHeader }px 0px 0px 0px` } ).observe( hero );

	const form = document.getElementById( 'preventivo' );
	if ( form ) {
		new IntersectionObserver( ( [ voce ] ) => {
			formVisibile = voce.isIntersecting;
			aggiorna();
		} ).observe( form );
	}
}() );

/* Tracciamento: eventi nel dataLayer (letti da GTM dopo il consenso), UTM e variante nel form. */
( function () {
	window.dataLayer = window.dataLayer || [];
	const variante = document.body.classList.contains( 'variante-b' ) ? 'b' : 'a';
	const CHIAVI_UTM = [ 'utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content' ];

	// UTM: dall'URL, ricordati per la sessione se il visitatore ricarica senza parametri.
	const parametri = new URLSearchParams( location.search );
	const utm = {};
	let salvati = {};
	try {
		salvati = JSON.parse( sessionStorage.getItem( 'nl_utm' ) || '{}' );
	} catch ( e ) {}
	CHIAVI_UTM.forEach( ( k ) => {
		utm[ k ] = parametri.get( k ) || salvati[ k ] || '';
	} );
	try {
		if ( CHIAVI_UTM.some( ( k ) => parametri.get( k ) ) ) {
			sessionStorage.setItem( 'nl_utm', JSON.stringify( utm ) );
		}
	} catch ( e ) {}

	const form = document.querySelector( '#preventivo .wpcf7-form' );
	const imposta = ( nome, valore ) => {
		const campo = form && form.querySelector( `[name="${ nome }"]` );
		if ( campo ) {
			campo.value = valore;
		}
	};

	if ( form ) {
		CHIAVI_UTM.forEach( ( k ) => imposta( k, utm[ k ] ) );
		imposta( 'variante', variante );
		imposta( 'richiesta', 'Preventivo' );
	}

	const posizione = ( el ) => {
		const cta = el.closest( '[data-cta]' );
		if ( cta ) {
			return cta.dataset.cta;
		}
		const sezione = el.closest( 'section[id], header, footer, .barra-cta' );
		return sezione ? ( sezione.id || sezione.className.split( ' ' )[ 0 ] ) : '';
	};

	const TIPI = { vetrina: 'Sito vetrina', blog: 'Sito + Blog', ecommerce: 'E-commerce' };

	document.addEventListener( 'click', ( e ) => {
		const link = e.target.closest( 'a' );
		if ( ! link ) {
			return;
		}

		// Telefono e WhatsApp.
		if ( link.dataset.evento ) {
			window.dataLayer.push( { event: link.dataset.evento, posizione: posizione( link ), variante } );
		}

		// CTA "Analizza gratis il mio sito": segna la richiesta ed evidenzia il campo del sito.
		if ( link.closest( '[data-richiesta="analisi"]' ) && link.matches( '[data-cta]' ) && form ) {
			imposta( 'richiesta', 'Analisi gratuita del sito' );
			const campoSito = form.querySelector( '.campo--sito' );
			if ( campoSito ) {
				setTimeout( () => campoSito.classList.add( 'is-evidenziato' ), 500 );
				setTimeout( () => campoSito.classList.remove( 'is-evidenziato' ), 3500 );
			}
		}

		// CTA di un pacchetto: preseleziona il tipo di sito nel form.
		const pacchetto = link.closest( '[data-pacchetto]' );
		if ( pacchetto && form ) {
			const scelta = form.querySelector( `input[name="tipo"][value="${ TIPI[ pacchetto.dataset.pacchetto ] }"]` );
			if ( scelta ) {
				scelta.checked = true;
			}
			imposta( 'pacchetto_cliccato', pacchetto.dataset.pacchetto );
		}
	} );

	// Invio riuscito: evento e pagina di ringraziamento.
	document.addEventListener( 'wpcf7mailsent', ( e ) => {
		const dati = Object.fromEntries( ( e.detail.inputs || [] ).map( ( i ) => [ i.name, i.value ] ) );
		const grazie = document.getElementById( 'preventivo' )?.dataset.grazie;
		window.dataLayer.push( {
			event: 'invio_form',
			tipo_sito: dati.tipo || '',
			richiesta: dati.richiesta || '',
			ha_sito: dati.sito_attuale ? 'sì' : 'no',
			variante,
			...utm,
		} );
		if ( grazie ) {
			setTimeout( () => {
				location.href = grazie;
			}, 300 );
		}
	} );
}() );
