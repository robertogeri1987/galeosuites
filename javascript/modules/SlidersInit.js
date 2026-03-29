class SlidersInit {

	constructor() {
		this.singleSwiperContainers = document.querySelectorAll('.acf-slider__container');
		this.swiperCarousels = document.querySelectorAll('.acf-slider__container--carousel-outer');
		this.swiperCarouselsFull = document.querySelectorAll('.acf-slider__container--carousel-outer-full');
		this.swiperCarouselsPerson = document.querySelectorAll('.acf-slider__container--carousel-outer-person');
		this.swiperCarouselsLabels = document.querySelectorAll('.acf-slider__container--carousel-outer-labels');

		if (this.singleSwiperContainers.length > 0 || this.swiperCarousels.length > 0 || this.swiperCarouselsLabels.length > 0 || this.swiperCarouselsPerson.length > 0 || this.swiperCarouselsFull.length > 0) {
			import('./SwiperModule.js').then(module => {
				this.Swiper = module.default;
				this.singleSwiperEvents();
				this.carouselSwiperEvents();
				this.carouselSwiperFullEvents();
				this.carouselSwiperLabelsEvents();
				this.carouselSwiperPersonEvents();
			});
		}
	}

	singleSwiperEvents() {

		for (const container of this.singleSwiperContainers) {

			let autoPlay = false;

			if (container.dataset.autoplay === 'true') {
				autoPlay = true;
			}

			let mainSwiper = new this.Swiper(container, {
				effect: 'fade',
				loop: false,
				autoplay: autoPlay,
				navigation: {
					nextEl: container.querySelector('.swiper-button-next'),
					prevEl: container.querySelector('.swiper-button-prev'),
				},
				pagination: {
					el: container.querySelector('.swiper-pagination'),
					clickable: true,
					renderBullet: function (index, className) {
						return '<div class="swiper-pagination-bullet"><svg class="svg-icon-bullet-filled"><use xlink:href="#bullet-filled"></use></svg><svg class="svg-icon-bullet-empty"><use xlink:href="#bullet-empty"></use></svg></div>';
					}
				},

			});

		}

	}

	carouselSwiperEvents() {

		for (const carouselContainer of this.swiperCarousels) {

			let thisSwiper = carouselContainer.querySelector('.acf-slider__container--carousel');
			let autoPlay = false;
			let loop = false;

			if (thisSwiper.dataset.loop === 'true') {
				loop = true;
			}

			if (thisSwiper.dataset.autoplay === 'true') {
				autoPlay = true;
			}

			let swiperCarousel = new this.Swiper(thisSwiper, {
				slidesPerView: 1.2,
				spaceBetween: 20,
				autoplay: autoPlay,
				freeMode: false,
				loop: loop,
				loopFillGroupWithBlank: false,
				navigation: {
					nextEl: carouselContainer.querySelector('.acf-slider__arrow--right'),
					prevEl: carouselContainer.querySelector('.acf-slider__arrow--left'),
				},
				breakpoints: {
					480: {
						slidesPerView: 1.3,
						spaceBetween: 20
					},
					700: {
						slidesPerView: 2.4,
						spaceBetween: 20,
					},
					1024: {
						slidesPerView: 3
					}
				}
			});

		}

	}

	carouselSwiperFullEvents() {

		for (const carouselContainer of this.swiperCarouselsFull) {

			let thisSwiper = carouselContainer.querySelector('.acf-slider__container--carousel');

			const equalizeSlideHeights = () => {
				const slides = carouselContainer.querySelectorAll('.swiper-slide');
				let maxHeight = 0;

				// Reset heights first
				slides.forEach(slide => {
					slide.style.height = 'auto';
					const slideHeight = slide.offsetHeight;
					maxHeight = Math.max(maxHeight, slideHeight);
				});

				// Apply equal height to all slides
				slides.forEach(slide => {
					slide.style.height = `${maxHeight}px`;
				});
			};

			let swiperCarousel = new this.Swiper(thisSwiper, {
				slidesPerView: 'auto',
				spaceBetween: 20,
				autoplay: false,
				freeMode: false,
				centeredSlides: true,
				loop: true,
				loopFillGroupWithBlank: false,
				navigation: {
					nextEl: carouselContainer.querySelector('.acf-slider__arrow--right'),
					prevEl: carouselContainer.querySelector('.acf-slider__arrow--left'),
				},
				on: {
					init: function () {
						// Initialize slide heights once loaded
						setTimeout(() => {
							equalizeSlideHeights();
						}, 100);
					},
					resize: function () {
						// Re-equalize after resize
						equalizeSlideHeights();
					}
				}
			});

			// Initialize PhotoSwipe lightbox
			this.initPhotoSwipeForSlider(carouselContainer, swiperCarousel);
		}
	}

	initPhotoSwipeForSlider(carouselContainer, swiperInstance) {
		import('./PhotoswipeModule.js').then(module => {
			const PhotoSwipeLightbox = module.default;

			const lightbox = new PhotoSwipeLightbox({
				gallery: carouselContainer.querySelector('.swiper-wrapper'),
				children: 'a.lightbox-trigger',
				pswpModule: () => import('photoswipe')
			});

			// Sync lightbox changes with swiper
			lightbox.on("change", () => {
				if (swiperInstance && lightbox.pswp) {
					swiperInstance.slideTo(lightbox.pswp.currIndex, false);
				}
			});

			lightbox.init();
		});
	}

	carouselSwiperLabelsEvents() {

		for (const carouselContainer of this.swiperCarouselsLabels) {

			const thisSwiper = carouselContainer.querySelector('.swiper-container');
			const currentEl = carouselContainer.querySelector('.swiper-current');
			const autoPlay = thisSwiper.dataset.autoplay === 'true';

			// 1. funzione helper che prende "swiper" come parametro
			const updateCounter = (swiper) => {
				if (!currentEl) return;
				const n = swiper.activeIndex + 1;          // 1‑based
				currentEl.textContent = String(n).padStart(2, '0');
			};

			// 2. crea l'istanza e usa funzioni che ricevono l'argomento
			const swiperCarousel = new this.Swiper(thisSwiper, {
				slidesPerView: 1,
				spaceBetween: 0,
				autoplay: autoPlay,
				freeMode: false,
				loop: false,
				loopFillGroupWithBlank: false,
				navigation: {
					nextEl: carouselContainer.querySelector('.acf-slider__arrow--right'),
					prevEl: carouselContainer.querySelector('.acf-slider__arrow--left'),
				},
				breakpoints: {
					480: { slidesPerView: 1, spaceBetween: 0 },
					700: { slidesPerView: 1, spaceBetween: 0 },
					1024: { slidesPerView: 1 },
				},
				on: {
					init: updateCounter,   // Swiper gli passerà (this)
					slideChange: updateCounter,
				},
			});

		}
	}


	carouselSwiperPersonEvents() {
		for (const personCarousel of this.swiperCarouselsPerson) {

			let thisSwiper = personCarousel.querySelector('.swiper-container');

			let swiperPersonCarousel = new this.Swiper(thisSwiper, {
				slidesPerView: 1.2,
				spaceBetween: 20,
				autoplay: false,
				loop: true,
				loopFillGroupWithBlank: false,
				simulateTouch: true,
				draggable: true,
				navigation: false,
				centeredSlides: true,
				breakpoints: {
					480: {
						slidesPerView: 1.2,
						spaceBetween: 20
					},
					700: {
						slidesPerView: 2.2,
						spaceBetween: 20,
					},
					1024: {
						slidesPerView: 4.3,
						spaceBetween: 20,
					}
				}
			});

		}
	}


}

export default SlidersInit;
