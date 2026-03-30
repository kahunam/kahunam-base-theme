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

	function closeMenu() {
		button.setAttribute( 'aria-expanded', 'false' );
		nav.classList.remove( 'menu-open' );
	}

	function openMenu() {
		button.setAttribute( 'aria-expanded', 'true' );
		nav.classList.add( 'menu-open' );
	}

	button.addEventListener( 'click', function() {
		if ( nav.classList.contains( 'menu-open' ) ) {
			closeMenu();
		} else {
			openMenu();
		}
	} );

	document.addEventListener( 'keydown', function( event ) {
		if ( event.key === 'Escape' && nav.classList.contains( 'menu-open' ) ) {
			closeMenu();
			button.focus();
		}
	} );

	window.addEventListener( 'resize', function() {
		if ( window.innerWidth > 768 && nav.classList.contains( 'menu-open' ) ) {
			closeMenu();
		}
	} );
} )();
