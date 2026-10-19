/* Pagina "Impostazioni landing": elenchi ripetibili e campi immagine. */
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
}() );
