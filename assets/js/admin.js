/* Pagina "Impostazioni landing": testimonianze ripetibili e scelta della foto. */
( function () {
	const lista = document.getElementById( 'nl-testimonianze' );
	const modello = document.getElementById( 'nl-testimonianza-modello' );

	document.getElementById( 'nl-aggiungi-testimonianza' ).addEventListener( 'click', () => {
		// Indice univoco: l'ordine viene ricompattato al salvataggio.
		const html = modello.innerHTML.replaceAll( '__i__', String( Date.now() ) );
		lista.insertAdjacentHTML( 'beforeend', html );
		lista.lastElementChild.querySelector( 'input' ).focus();
	} );

	lista.addEventListener( 'click', ( e ) => {
		if ( e.target.classList.contains( 'nl-rimuovi-testimonianza' ) ) {
			e.target.closest( '.nl-testimonianza' ).remove();
		}
	} );

	const campo = document.getElementById( 'nl-foto-id' );
	const anteprima = document.getElementById( 'nl-foto-anteprima' );
	const rimuovi = document.getElementById( 'nl-foto-rimuovi' );
	let frame;

	document.getElementById( 'nl-foto-scegli' ).addEventListener( 'click', () => {
		frame = frame || wp.media( {
			title: 'Foto di Matteo',
			button: { text: 'Usa questa foto' },
			library: { type: 'image' },
			multiple: false,
		} );
		frame.off( 'select' ).on( 'select', () => {
			const foto = frame.state().get( 'selection' ).first().toJSON();
			const url = ( foto.sizes && ( foto.sizes.medium || foto.sizes.thumbnail ) || foto ).url;
			campo.value = foto.id;
			anteprima.innerHTML = '';
			const img = document.createElement( 'img' );
			img.src = url;
			img.alt = '';
			anteprima.appendChild( img );
			rimuovi.hidden = false;
		} );
		frame.open();
	} );

	rimuovi.addEventListener( 'click', () => {
		campo.value = '';
		anteprima.innerHTML = '';
		rimuovi.hidden = true;
	} );
}() );
