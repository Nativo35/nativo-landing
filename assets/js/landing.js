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
