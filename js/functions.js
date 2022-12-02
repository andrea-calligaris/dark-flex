/**
 * Theme functions file
 *
 * Menu toggle for mobile, and scroll-to-top button.
 */

( function( $ ) {

	var $body, $window, $header, thisMainNavigation, buttonMenuToggle;

	// check if target matches to an element
	function targetMatches(selector) {
		return event.target.matches ? event.target.matches(selector) : event.target.msMatchesSelector(selector);
	}

	function initMainNavigation( container ) {
		// add dropdown toggle that displays child menu items
		var dropdownToggle = $( '<button />', {
			'class': 'dropdown-toggle',
			'aria-expanded': false
		} ).append( $( '<span />', {
			'class': 'screen-reader-text',
			text: screenReaderText.expand
		} ) );
		container.find( '.menu-item-has-children > a' ).after( dropdownToggle );

		// toggle buttons and submenu items with active children menu items
		container.find( '.current-menu-ancestor > button' ).addClass( 'toggled-on' );
		container.find( '.current-menu-ancestor > .sub-menu' ).addClass( 'toggled-on' );

		// add menu items with submenus to aria-haspopup="true"
		container.find( '.menu-item-has-children' ).attr( 'aria-haspopup', 'true' );

		container.find( '.dropdown-toggle' ).on( 'click', function( e ) {
			var _this            = $( this ),
				screenReaderSpan = _this.find( '.screen-reader-text' );

			e.preventDefault();
			_this.toggleClass( 'toggled-on' );
			_this.next( '.children, .sub-menu' ).toggleClass( 'toggled-on' );

			// jscs:disable
			_this.attr( 'aria-expanded', _this.attr( 'aria-expanded' ) === 'false' ? 'true' : 'false' );
			// jscs:enable
			screenReaderSpan.text( screenReaderSpan.text() === screenReaderText.expand ? screenReaderText.collapse : screenReaderText.expand );
		} );
	}
	initMainNavigation( $( '.main-navigation' ) );

	const MOBILE_WIDTH_LIMIT = 956;

	$window	= $( window );
	$body	= $( document.body );
	$header = $( '.site-header' );
	buttonMenuToggle = $( '.site-branding' ).find( '.button-top-menu-toggle' );
	thisMainNavigation = $( '.main-navigation' );

	// enable menu toggle for small screens
	( function() {
		var menu, widgets, social;
		if ( ! thisMainNavigation.length || ! buttonMenuToggle.length ) {
			return;
		}

		// add an initial values for the attribute
		buttonMenuToggle.add( thisMainNavigation ).attr( 'aria-expanded', 'false' );

		// Button Menu Toggle - On Click
		buttonMenuToggle.on( 'click.twentyfifteen', function() {
			buttonMenuToggle.toggleClass( 'toggled-on' );
			thisMainNavigation.toggleClass( 'toggled-on' );
			// jscs:disable
			if ( thisMainNavigation.hasClass( 'toggled-on' ) ) {
				buttonMenuToggle.attr( 'aria-expanded', 'true' );
				thisMainNavigation.attr( 'aria-expanded', 'true' );
			} else {
				buttonMenuToggle.attr( 'aria-expanded', 'false' );
				thisMainNavigation.attr( 'aria-expanded', 'false' );
			}
			// jscs:enable
		} );
	} )();
	
	// fix sub-menus for touch devices and better focus for hidden submenu items for accessibility
	( function() {
		if ( ! thisMainNavigation.length || ! thisMainNavigation.children().length ) {
			return;
		}

		// toggle `focus` class to allow submenu access on tablets
		function toggleFocusClassTouchScreen() {
			if ( $window.innerWidth >= MOBILE_WIDTH_LIMIT ) {
				$body.on( 'touchstart.twentyfifteen', function( e ) {
					if ( ! $( e.target ).closest( '.main-navigation li' ).length ) {
						$( '.main-navigation li' ).removeClass( 'focus' );
					}
				} );
				thisMainNavigation.find( '.menu-item-has-children > a' ).on( 'touchstart.twentyfifteen', function( e ) {
					var el = $( this ).parent( 'li' );

					if ( ! el.hasClass( 'focus' ) ) {
						e.preventDefault();
						el.toggleClass( 'focus' );
						el.siblings( '.focus' ).removeClass( 'focus' );
					}
				} );
			} else {
				thisMainNavigation.find( '.menu-item-has-children > a' ).unbind( 'touchstart.twentyfifteen' );
			}
		}

		if ( 'ontouchstart' in window ) {
			$window.on( 'resize.twentyfifteen', toggleFocusClassTouchScreen );
			toggleFocusClassTouchScreen();
		}

		thisMainNavigation.find( 'a' ).on( 'focus.twentyfifteen blur.twentyfifteen', function() {
			$( this ).parents( '.menu-item' ).toggleClass( 'focus' );
		} );
	} )();

	// On Window Resize, add the default ARIA attributes for the menu toggle and the navigation
	function onWindowResized() {
		if ( window.innerWidth < MOBILE_WIDTH_LIMIT ) {
			if ( buttonMenuToggle.hasClass( 'toggled-on' ) ) {
				buttonMenuToggle.attr( 'aria-expanded', 'true' );
			} else {
				buttonMenuToggle.attr( 'aria-expanded', 'false' );
			}

			if ( thisMainNavigation.hasClass( 'toggled-on' ) ) {
				thisMainNavigation.attr( 'aria-expanded', 'true' );
			} else {
				thisMainNavigation.attr( 'aria-expanded', 'false' );
			}

			buttonMenuToggle.attr( 'aria-controls', 'site-navigation' );
		} else {
			buttonMenuToggle.removeAttr( 'aria-expanded' );
			thisMainNavigation.removeAttr( 'aria-expanded' );
			buttonMenuToggle.removeAttr( 'aria-controls' );
		}
	}

	$( function() {
		// when the theme is loaded for the first time and when the window is resized, call the onWindowResized function to set the visibility of the elements:
		$window.on( 'load.twentyfifteen', onWindowResized );
		$window.on( 'resize.twentyfifteen', onWindowResized );
	} );

	// scroll to top
	function scrollTo(final, duration, cb) {
		var document_scrollTop = $window.scrollY || document.documentElement.scrollTop,
			current_time = null;

		var animateScroll = function(timestamp) {
			if(!current_time) {
				current_time = timestamp;
			}

			var progress = timestamp - current_time;

			if(progress > duration) {
				progress = duration;
			}

			var animValue = Math.easeInOutQuad(progress, document_scrollTop, final - document_scrollTop, duration);

			window.scrollTo(0, animValue);
			if(progress < duration) {
				window.requestAnimationFrame(animateScroll);
			} else {
				cb && cb();
			}
		};

		window.requestAnimationFrame(animateScroll);
	};

	// animation curves for scrolling to top
	Math.easeInOutQuad = function (t, b, c, d) {
		t /= d/2;
		if(t < 1) return c/2*t*t + b;
		t--;
		return -c/2 * (t*(t-2) - 1) + b;
	};

	// "scroll to top" button
	var button_back_to_top = document.querySelector('.back-to-top');
	if(button_back_to_top) {
		var animOffset = 300;
		var offsetOpacity = 1200;
		var scrollDuration = 700;
		var is_scrolling = false;
		window.addEventListener('scroll', function() {
			if(!is_scrolling) {
				is_scrolling = true;
				(!window.requestAnimationFrame) ? setTimeout(checkBackToTop, 250) : window.requestAnimationFrame(checkBackToTop);
			}
		});

		document.addEventListener('click', function(event) {
			if(targetMatches('.back-to-top')) {
				event.preventDefault();
				(!window.requestAnimationFrame) ? scrollTo(0, 0) : scrollTo(0, scrollDuration);
			}
		});
	}

	function checkBackToTop() {
		var windowTop = window.scrollY || document.documentElement.scrollTop;
		( windowTop > animOffset ) ? button_back_to_top.classList.add('back-to-top--show') : button_back_to_top.classList.remove('back-to-top--show', 'back-to-top--fade-out');
		( windowTop > offsetOpacity ) && button_back_to_top.classList.add('back-to-top--fade-out');
		is_scrolling = false;
	}

} )( jQuery );