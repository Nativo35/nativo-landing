/* Pagina "Impostazioni landing": schede, sezioni, elenchi ripetibili e campi immagine. */
( function () {
	// Elenchi ripetibili (testimonianze, progetti).
	document.querySelectorAll( '[data-aggiungi]' ).forEach( ( pulsante ) => {
		const chiave = pulsante.dataset.aggiungi;
		const lista = document.querySelector( `[data-ripetitore="${ chiave }"]` );
		const modello = document.querySelector( `[data-modello="${ chiave }"]` );

		pulsante.addEventListener( 'click', () => {
			// Indice univoco: l'ordine viene ricompattato al salvataggio.
			lista.insertAdjacentHTML( 'beforeend', modello.innerHTML.replaceAll( '__i__', String( Date.now() ) ) );
			lista.lastElementChild.querySelector( 'input' ).focus();
		} );
	} );

	document.addEventListener( 'click', ( e ) => {
		if ( e.target.classList.contains( 'nl-rimuovi-voce' ) ) {
			e.target.closest( '.nl-voce' ).remove();
		}
	} );

	// Campi immagine: delega sul documento, così funzionano anche nelle voci aggiunte.
	let frame;
	let campoAttivo;

	document.addEventListener( 'click', ( e ) => {
		const campo = e.target.closest( '.nl-media' );
		if ( ! campo ) {
			return;
		}

		if ( e.target.classList.contains( 'nl-media__rimuovi' ) ) {
			campo.querySelector( '.nl-media__id' ).value = '';
			campo.querySelector( '.nl-media__anteprima' ).innerHTML = '';
			e.target.hidden = true;
			return;
		}

		if ( ! e.target.classList.contains( 'nl-media__scegli' ) ) {
			return;
		}

		campoAttivo = campo;
		frame = frame || wp.media( {
			title: 'Scegli un\'immagine',
			button: { text: 'Usa questa immagine' },
			library: { type: 'image' },
			multiple: false,
		} );
		frame.off( 'select' ).on( 'select', () => {
			const img = frame.state().get( 'selection' ).first().toJSON();
			const url = ( img.sizes && ( img.sizes.medium || img.sizes.thumbnail ) || img ).url;
			const anteprima = document.createElement( 'img' );
			anteprima.src = url;
			anteprima.alt = '';
			campoAttivo.querySelector( '.nl-media__id' ).value = img.id;
			campoAttivo.querySelector( '.nl-media__anteprima' ).replaceChildren( anteprima );
			campoAttivo.querySelector( '.nl-media__rimuovi' ).hidden = false;
		} );
		frame.open();
	} );

	// Schede (Generale, Sezioni): un solo modulo, si salvano insieme. Senza JavaScript sono una sotto l'altra.
	const schede = Array.from( document.querySelectorAll( '.nl-scheda' ) );
	const linguette = Array.from( document.querySelectorAll( '.nav-tab[data-scheda]' ) );
	const referer = document.querySelector( '.nl-admin input[name="_wp_http_referer"]' );
	const refererBase = referer ? referer.value.split( '#' )[ 0 ] : '';

	const mostraScheda = ( id ) => {
		schede.forEach( ( s ) => ( s.hidden = s.id !== id ) );
		linguette.forEach( ( l ) => {
			l.classList.toggle( 'nav-tab-active', l.dataset.scheda === id );
			l.setAttribute( 'aria-current', l.dataset.scheda === id ? 'page' : 'false' );
		} );
		// Dopo il salvataggio si torna sulla stessa scheda.
		if ( referer ) {
			referer.value = refererBase + '#' + id;
		}
	};

	// Anche un'ancora interna a una scheda (es. #tracciamento) apre la scheda giusta.
	const daHash = () => {
		const bersaglio = location.hash && document.getElementById( location.hash.slice( 1 ) );
		const scheda = bersaglio && bersaglio.closest( '.nl-scheda' );
		mostraScheda( scheda ? scheda.id : 'generale' );
		if ( bersaglio && bersaglio !== scheda ) {
			bersaglio.scrollIntoView();
		}
	};
	if ( schede.length ) {
		daHash();
		window.addEventListener( 'hashchange', daHash );
	}

	// Sezioni: dispositivi in grigio con "Attiva" spento, avvisi per Hero e Form.
	const righe = Array.from( document.querySelectorAll( '.nl-sezione' ) );
	const aggiornaRiga = ( riga ) => {
		const attiva = riga.querySelector( '.nl-attiva' );
		const spenta = attiva ? ! attiva.checked : false;
		riga.classList.toggle( 'is-spenta', spenta );
		riga.querySelectorAll( '.nl-dispositivo' ).forEach( ( c ) => ( c.disabled = spenta ) );

		const avviso = riga.querySelector( '.nl-avviso' );
		if ( avviso ) {
			const tolti = Array.from( riga.querySelectorAll( '.nl-dispositivo:not(:checked)' ) ).map( ( c ) => c.dataset.nome );
			avviso.hidden = ! tolti.length;
			avviso.textContent = avviso.dataset.testo + tolti.join( ', ' ) + '.';
		}
	};
	righe.forEach( ( riga ) => {
		aggiornaRiga( riga );
		riga.addEventListener( 'change', () => aggiornaRiga( riga ) );
	} );

	// I campi disabilitati non vengono inviati: si riattivano al salvataggio, così i dispositivi scelti restano.
	const modulo = document.querySelector( '.nl-admin form' );
	if ( modulo ) {
		modulo.addEventListener( 'submit', () => {
			modulo.querySelectorAll( '.nl-dispositivo:disabled' ).forEach( ( c ) => ( c.disabled = false ) );
		} );
	}

	const ripristina = document.getElementById( 'nl-ripristina-sezioni' );
	if ( ripristina && modulo ) {
		ripristina.addEventListener( 'click', () => {
			righe.forEach( ( riga ) => {
				riga.querySelectorAll( '.nl-attiva, .nl-dispositivo' ).forEach( ( c ) => ( c.checked = true ) );
				aggiornaRiga( riga );
			} );
			modulo.requestSubmit();
		} );
	}
}() );
