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

/* Carosello delle testimonianze: frecce, pallini, pausa e avanzamento automatico ogni 6 secondi.
   Si ferma con mouse sopra, tocco, tastiera, sezione fuori schermo o scheda nascosta; mai automatico con movimento ridotto. */
( function () {
	const sezione = document.getElementById( 'testimonianze' );
	const elenco = document.getElementById( 'carosello-testimonianze' );
	const comandi = sezione && sezione.querySelector( '.carosello__comandi' );
	if ( ! elenco || ! comandi ) {
		return;
	}

	const voci = Array.from( elenco.children );
	const ridotto = window.matchMedia( '(prefers-reduced-motion: reduce)' );
	const INTERVALLO = 6000;
	const pallini = comandi.querySelector( '.carosello__pallini' );
	const indietro = comandi.querySelector( '[data-carosello="indietro"]' );
	const avanti = comandi.querySelector( '[data-carosello="avanti"]' );
	const pausa = comandi.querySelector( '[data-carosello="pausa"]' );

	let fermataManuale = ridotto.matches; // con movimento ridotto si parte fermi
	let sospesa = false; // mouse sopra, focus, tocco
	let visibile = false;
	let timer = null;

	const passo = () => {
		const d = voci[ 1 ] ? voci[ 1 ].getBoundingClientRect().left - voci[ 0 ].getBoundingClientRect().left : 0;
		return d > 0 ? d : elenco.clientWidth; // d = 0 se gli stili delle sezioni non sono ancora arrivati
	};
	const quanteVisibili = () => Math.max( 1, Math.round( elenco.clientWidth / passo() ) );
	const ultimaPosizione = () => Math.max( 0, voci.length - quanteVisibili() );
	const posizione = () => Math.min( ultimaPosizione(), Math.round( elenco.scrollLeft / passo() ) );

	const vaiA = ( i ) => {
		const destinazione = Math.max( 0, Math.min( i, ultimaPosizione() ) );
		elenco.scrollTo( { left: destinazione * passo(), behavior: ridotto.matches ? 'auto' : 'smooth' } );
	};

	// Un pallino per ogni posizione raggiungibile (con 3 card visibili: card totali − 2).
	const creaPallini = () => {
		const totale = ultimaPosizione() + 1;
		if ( pallini.children.length === totale ) {
			segnaAttivo();
			return;
		}
		pallini.replaceChildren();
		for ( let i = 0; i < totale; i++ ) {
			const b = document.createElement( 'button' );
			b.type = 'button';
			b.className = 'carosello__pallino';
			b.setAttribute( 'aria-label', `Vai alla testimonianza ${ i + 1 } di ${ totale }` );
			b.addEventListener( 'click', () => {
				fermataManuale = true;
				aggiornaPausa();
				vaiA( i );
			} );
			pallini.appendChild( b );
		}
		const attivo = ultimaPosizione() > 0;
		comandi.hidden = ! attivo;
		sezione.classList.toggle( 'is-carosello', attivo );
		segnaAttivo();
	};

	const segnaAttivo = () => {
		const p = posizione();
		pallini.querySelectorAll( '.carosello__pallino' ).forEach( ( b, i ) => b.setAttribute( 'aria-current', String( i === p ) ) );
	};

	const aggiornaPausa = () => {
		pausa.classList.toggle( 'is-ferma', fermataManuale );
		pausa.setAttribute( 'aria-label', fermataManuale ? 'Avvia lo scorrimento automatico' : 'Metti in pausa lo scorrimento automatico' );
		// Durante lo scorrimento automatico gli annunci agli screen reader sono spenti.
		elenco.setAttribute( 'aria-live', fermataManuale ? 'polite' : 'off' );
		pianifica();
		segnaAttivo();
	};

	const pianifica = () => {
		clearInterval( timer );
		timer = null;
		if ( ! fermataManuale && ! sospesa && visibile && ! document.hidden && ultimaPosizione() > 0 ) {
			timer = setInterval( () => {
				const p = posizione();
				vaiA( p >= ultimaPosizione() ? 0 : p + 1 );
			}, INTERVALLO );
		}
	};

	indietro.addEventListener( 'click', () => {
		const p = posizione();
		vaiA( p <= 0 ? ultimaPosizione() : p - 1 );
		pianifica();
	} );
	avanti.addEventListener( 'click', () => {
		const p = posizione();
		vaiA( p >= ultimaPosizione() ? 0 : p + 1 );
		pianifica();
	} );
	pausa.addEventListener( 'click', () => {
		fermataManuale = ! fermataManuale;
		aggiornaPausa();
	} );

	const sospendi = ( stato ) => () => {
		sospesa = stato;
		pianifica();
	};
	sezione.addEventListener( 'mouseenter', sospendi( true ) );
	sezione.addEventListener( 'mouseleave', sospendi( false ) );
	sezione.addEventListener( 'focusin', sospendi( true ) );
	sezione.addEventListener( 'focusout', ( e ) => {
		if ( ! sezione.contains( e.relatedTarget ) ) {
			sospendi( false )();
		}
	} );
	// Chi scorre con il dito prende il controllo: il carosello si ferma.
	elenco.addEventListener( 'touchstart', () => {
		fermataManuale = true;
		aggiornaPausa();
	}, { passive: true } );

	let rAF = 0;
	elenco.addEventListener( 'scroll', () => {
		cancelAnimationFrame( rAF );
		rAF = requestAnimationFrame( segnaAttivo );
	}, { passive: true } );

	new IntersectionObserver( ( [ voce ] ) => {
		visibile = voce.isIntersecting;
		if ( visibile ) {
			creaPallini(); // ricalcola con gli stili definitivi
		}
		pianifica();
	}, { threshold: 0.3 } ).observe( elenco );

	document.addEventListener( 'visibilitychange', pianifica );
	window.addEventListener( 'resize', () => {
		creaPallini();
		pianifica();
	} );
	ridotto.addEventListener( 'change', () => {
		if ( ridotto.matches ) {
			fermataManuale = true;
			aggiornaPausa();
		}
	} );

	// sezioni.css arriva senza bloccare la pagina: si parte a caricamento completato.
	const avvia = () => {
		creaPallini();
		aggiornaPausa();
	};
	if ( document.readyState === 'complete' ) {
		avvia();
	} else {
		window.addEventListener( 'load', avvia );
	}
}() );
