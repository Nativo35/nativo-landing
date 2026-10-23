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

/* Testimonianze, versione B: schede (tab) con una citazione alla volta e avanzamento automatico ogni 8 secondi.
   Stesse pause del carosello: mouse sopra, focus, sezione fuori schermo, scheda nascosta; mai automatico con movimento ridotto. */
( function () {
	const sezione = document.querySelector( '.testimonianze-evidenza' );
	if ( ! sezione ) {
		return;
	}
	const elenco = sezione.querySelector( '.evidenza__persone' );
	const schede = Array.from( sezione.querySelectorAll( '.evidenza__persona' ) );
	const pannelli = Array.from( sezione.querySelectorAll( '.evidenza__citazione' ) );
	const pausa = sezione.querySelector( '.evidenza__pausa' );
	if ( schede.length < 2 ) {
		return;
	}

	const ridotto = window.matchMedia( '(prefers-reduced-motion: reduce)' );
	const DURATA = 8000;
	let attiva = 0;
	let fermataManuale = ridotto.matches;
	let sospesa = false;
	let visibile = false;
	let timer = null;

	sezione.style.setProperty( '--durata', DURATA + 'ms' );

	const mostra = ( i, focus ) => {
		attiva = ( i + schede.length ) % schede.length;
		schede.forEach( ( s, n ) => {
			const sel = n === attiva;
			s.setAttribute( 'aria-selected', String( sel ) );
			s.tabIndex = sel ? 0 : -1;
			pannelli[ n ].hidden = ! sel;
		} );
		// Riavvia la barra di avanzamento della scheda attiva.
		const barra = schede[ attiva ].querySelector( '.evidenza__avanzamento' );
		barra.style.animation = 'none';
		void barra.offsetWidth;
		barra.style.animation = '';
		// Su mobile la scheda attiva resta visibile nella riga dei cerchi.
		if ( elenco.scrollWidth > elenco.clientWidth ) {
			elenco.scrollTo( { left: schede[ attiva ].offsetLeft - elenco.offsetLeft - 8, behavior: ridotto.matches ? 'auto' : 'smooth' } );
		}
		if ( focus ) {
			schede[ attiva ].focus();
		}
		pianifica();
	};

	const pianifica = () => {
		clearTimeout( timer );
		const inCorsa = ! fermataManuale && visibile && ! document.hidden;
		sezione.classList.toggle( 'is-in-corsa', inCorsa );
		sezione.classList.toggle( 'is-sospesa', sospesa );
		if ( inCorsa && ! sospesa ) {
			timer = setTimeout( () => mostra( attiva + 1 ), DURATA );
		}
	};

	const aggiornaPausa = () => {
		pausa.classList.toggle( 'is-ferma', fermataManuale );
		pausa.setAttribute( 'aria-label', fermataManuale ? 'Avvia lo scorrimento automatico' : 'Metti in pausa lo scorrimento automatico' );
		pianifica();
	};

	schede.forEach( ( s, n ) => {
		s.addEventListener( 'click', () => {
			fermataManuale = true; // chi sceglie una persona vuole leggerla con calma
			aggiornaPausa();
			mostra( n );
		} );
		s.addEventListener( 'keydown', ( e ) => {
			const tasti = { ArrowRight: 1, ArrowDown: 1, ArrowLeft: -1, ArrowUp: -1 };
			if ( e.key in tasti || e.key === 'Home' || e.key === 'End' ) {
				e.preventDefault();
				fermataManuale = true;
				aggiornaPausa();
				mostra( e.key === 'Home' ? 0 : e.key === 'End' ? schede.length - 1 : attiva + tasti[ e.key ], true );
			}
		} );
	} );

	pausa.addEventListener( 'click', () => {
		fermataManuale = ! fermataManuale;
		aggiornaPausa();
	} );

	const sospendi = ( stato ) => () => {
		sospesa = stato;
		if ( ! stato ) {
			// Alla ripresa riparte il conto per la citazione attuale.
			mostra( attiva );
		} else {
			pianifica();
		}
	};
	sezione.addEventListener( 'mouseenter', sospendi( true ) );
	sezione.addEventListener( 'mouseleave', sospendi( false ) );
	sezione.addEventListener( 'focusin', sospendi( true ) );
	sezione.addEventListener( 'focusout', ( e ) => {
		if ( ! sezione.contains( e.relatedTarget ) ) {
			sospendi( false )();
		}
	} );

	new IntersectionObserver( ( [ voce ] ) => {
		visibile = voce.isIntersecting;
		pianifica();
	}, { threshold: 0.3 } ).observe( sezione );
	document.addEventListener( 'visibilitychange', pianifica );
	ridotto.addEventListener( 'change', () => {
		if ( ridotto.matches ) {
			fermataManuale = true;
			aggiornaPausa();
		}
	} );

	// Attivazione: schede e pausa visibili, una citazione alla volta.
	elenco.hidden = false;
	pausa.hidden = false;
	sezione.classList.add( 'is-schede' );
	aggiornaPausa();
	mostra( 0 );
}() );
