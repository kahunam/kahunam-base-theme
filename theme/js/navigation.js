/**
 * Mobile navigation toggle.
 */
( function() {
	const nav = document.getElementById( 'site-navigation' );
	if ( ! nav ) {
		return;
	}

	const button = nav.querySelector( '.menu-toggle' );
	if ( ! button ) {
		return;
	}

	button.addEventListener( 'click', function() {
		const expanded = button.getAttribute( 'aria-expanded' ) === 'true';
		button.setAttribute( 'aria-expanded', String( ! expanded ) );
		nav.classList.toggle( 'menu-open' );
	} );
} )();
