import { _setCookie } from './Utilities.js';

class APWPPopup {

	constructor() {

		this.allPopups = document.querySelectorAll('.popup__grabber');
		this.allOpenPopups = document.querySelectorAll('.openpopup');
		this.allClosePopups = document.querySelectorAll('.popup__close');
		this.allStandardPopups = document.querySelectorAll('.popup');
		this.swiperCarouselPopup = document.getElementById('popup-swiper');

		if (this.allOpenPopups.length > 0) this.openEvents();
		if (this.allClosePopups.length > 0) this.closeEvents();
		if (this.allStandardPopups.length > 0) this.clickOutsideEvents();
		if (this.allPopups.length > 0) this.triggeredPopups();

		if (this.swiperCarouselPopup) {
			import('./SwiperModule.js').then(module => {
				this.Swiper = module.default;
				this.initSwiper();
			});
		}

	}

	openEvents() {

		for (const openPopup of this.allOpenPopups) {

			openPopup.addEventListener('click', (e) => {

				e.preventDefault();
				e.stopPropagation();

				let popUpID = document.querySelector(`#${openPopup.dataset.popup}`)

				popUpID.classList.add('showpopup');
				popUpID.querySelector('.popup__content').classList.add('showpopup');

			});

		}

	}

	closeEvents() {

		//Close non-slider Popups
		for (const closePopup of this.allClosePopups) {

			closePopup.addEventListener('click', (e) => {

				e.preventDefault();
				e.stopPropagation();

				let popUpID = document.querySelector(`#${closePopup.dataset.popup}`)

				popUpID.classList.remove('showpopup');
				popUpID.querySelector('.popup__content').classList.remove('showpopup');

			});

		}

	}

	clickOutsideEvents() {

		// Close popup on click outside
		for (const popup of this.allStandardPopups) {

			popup.addEventListener('click', (e) => {

				if (!e.target.closest('.popup__content')) {
					if (popup.classList.contains('showpopup')) {
						popup.classList.remove('showpopup');
						popup.querySelector('.popup__content').classList.remove('showpopup');
					}
				}

			});

		}

	}

	triggeredPopups() {

		for (const popup of this.allPopups) {

			document.body.insertAdjacentElement('beforeend', popup);

			let popupIndex = popup.dataset.popupindex;

			if (popupIndex) {

				if (popupParams.popupData[popupIndex].abilitato) {

					if (!popupParams.popupData[popupIndex].cookieSet) {

						setTimeout(() => {

							popup.classList.add('showpopup');
							popup.querySelector('.popup__content').classList.add('showpopup');
							// Sticky button
							popup.previousElementSibling.classList.add('hidden');

							if (popupParams.popupData[popupIndex].cookieName) {
								_setCookie(popupParams.popupData[popupIndex].cookieName, '1', 30 * 12);
							}

						}, popupParams.popupData[popupIndex].delay);

					}

					if (popupParams.popupData[popupIndex].alwaysShow) {

						if (!(popup.classList.contains('showpopup'))) {

							popup.classList.add('showpopup');
							popup.querySelector('.popup__content').classList.add('showpopup');
							// Sticky button
							popup.previousElementSibling.classList.add('hidden');

						}

					}

					if (popupParams.popupData[popupIndex].isSlider) {

						this.popupSliderEvents(popup, popupIndex);

					}

				}
			}
		}

	}

	popupSliderEvents(popup, popupIndex) {

		//Open Popup
		popup.previousElementSibling.addEventListener('click', (e) => {

			e.preventDefault();

			let sliderPopUpID = document.querySelector(`#${popup.id}`)

			sliderPopUpID.classList.add('showpopup');
			sliderPopUpID.querySelector('.popup__content').classList.add('showpopup');
			sliderPopUpID.previousElementSibling.classList.add('hidden');

		});

		// Close Popup
		popup.querySelector('.popup__close--info').addEventListener('click', (e) => {

			e.preventDefault();
			popup.classList.remove('showpopup');
			popup.querySelector('.popup__content').classList.remove('showpopup');
			popup.previousElementSibling.classList.remove('hidden');

			if (!popupParams.popupData[popupIndex].cookieSet) {
				_setCookie(popupParams.popupData[popupIndex].cookieName, '1', 30 * 12);
			}

		});

	}

	initSwiper() {

		let carouselPopup = new this.Swiper(this.swiperCarouselPopup, {
			autoplay: true,
			slidesPerView: 3,
			spaceBetween: 20,
			loop: false,
			navigation: {
				nextEl: '.popup__arrow-right',
				prevEl: '.popup__arrow-left',
			},
			breakpoints: {
				300: {
					slidesPerView: 1.5,
					loop: false
				},
				440: {
					slidesPerView: 2.1,
					loop: false
				},
				768: {
					slidesPerView: 3.7,
					loop: false
				},
				1024: {
					slidesPerView: 3,
					loop: false
				},
			}

		});

	}

}

export default APWPPopup;
