import * as $ from 'jquery';

class ScrollEvents {

	constructor() {

		//Back to top button
		let offset = 250;
		let duration = 300;

		$(window).on('scroll', function () {
			if ($(this).scrollTop() > offset) {
				$('.footer__back-to-top').fadeIn(duration);
			} else {
				$('.footer__back-to-top').fadeOut(duration);
			}
		});

		$('.footer__back-to-top').on('click', function (event) {
			event.preventDefault();
			$('html, body').animate({ scrollTop: 0 }, duration);
			return false;
		});

		// Add id to top of page for scrolling target.
		$('html').attr('id', 'top');

		/**
		 * Smooth scrolling.
		 */
		// Select all links with hashes
		var offHeight = document.querySelector('.header');
		if (offHeight) {
			offHeight = offHeight.offsetHeight
		} else {
			offHeight = 0;
		}
		$('a[href*="#"]')
			// Remove links that don't actually link to anything
			.not('[href="#"]')
			.not('[href="#0"]')
			// Remove WooCommerce tabs
			.not('[href*="#tab-"]')
			.on('click', function (event) {
				// On-page links
				if (
					location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') &&
					location.hostname == this.hostname
				) {
					// Figure out element to scroll to
					var target = $(this.hash);
					target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
					// Does a scroll target exist?
					if (target.length) {
						// Only prevent default if animation is actually gonna happen
						event.preventDefault();
						$('html, body').animate({
							scrollTop: target.offset().top - offHeight - 150
						}, 1000, function () {
							// Callback after animation
							// Must change focus!
							var $target = $(target);
							$target.focus();
							if ($target.is(":focus")) { // Checking if the target was focused
								return false;
							} else {
								$target.attr('tabindex', '-1'); // Adding tabindex for elements not focusable
								$target.focus(); // Set focus again
							};
						});
					}
				}
			});

	}

}

export default ScrollEvents;
