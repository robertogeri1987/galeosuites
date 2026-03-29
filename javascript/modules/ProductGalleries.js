import * as $ from 'jquery';

// core version + selected modules:
class ProductGalleries {

	constructor() {

		this.galleryThumbs;
		this.galleryTop;
		this.gallery;
		this.galleryThumbsEl = document.querySelector('.gallery-thumbs');
		this.galleryTopEl = document.querySelector('.gallery-top');
		this.photoSwipeEl = document.querySelector('.my-gallery');
		this.summaryDiv = document.querySelector('.summary');

		if (this.galleryThumbsEl || this.galleryTopEl) {

			import('./SwiperModule.js').then(module => {

				this.Swiper = module.default;

				import('./PhotoswipeModule.js').then(module => {
					this.PhotoSwipeLightbox = module.default;

					if (this.galleryThumbsEl) this.thumbsGalleryInit();
					if (this.galleryTopEl) this.galleryTopInit();
					if (this.summaryDiv) this.variationChangeEvents();
				});

			});

		}

		$('.add_variation_images').on('click', function (e) {
			e.preventDefault();
			var button = $(this);
			var target = button.data('target');
			var uploader = wp.media({
				title: button.data('uploader-title'),
				button: {
					text: button.data('uploader-button-text'),
				},
				multiple: true
			}).on('select', function () {
				var selection = uploader.state().get('selection');
				var images = [];
				selection.map(function (attachment) {
					attachment = attachment.toJSON();
					images.push(attachment.id);
				});
				$(target).val(images.join(','));
				var container = $(target).siblings('.variation_image_gallery_container');
				container.empty();
				images.forEach(function (image) {
					container.append(wp.media.string.image(image));
				});
			}).open();
		});


	}

	thumbsGalleryInit() {

		this.galleryThumbs = new this.Swiper('.gallery-thumbs', {
			init: false,
			spaceBetween: 20,
			centeredSlides: false,
			slidesPerView: 4,
			watchOverflow: true,
			watchSlidesVisibility: true,
			watchSlidesProgress: true,
			direction: 'vertical'
		});

		this.galleryThumbs.init()

	}

	galleryTopInit() {

		let numSlides = this.galleryTopEl.querySelectorAll('li');

		this.galleryTop = new this.Swiper('.gallery-top', {
			init: false,
			spaceBetween: 0,
			loop: false,
			loopPreventsSlide: false,
			slidesPerView: 1,
			preloadImages: true,
			loopFillGroupWithBlank: false,
			updateOnImagesReady: true,
			updateOnWindowResize: true,
			observer: true,
			observeParents: true,
			simulateTouch: (numSlides.length > 3) ? true : false,
			draggable: (numSlides.length > 3) ? true : false,
			navigation: false,
			thumbs: {
				swiper: this.galleryThumbsEl ? this.galleryThumbs : '',
			}
		});

		if (this.galleryThumbs) {
			this.galleryTop.on('slideChangeTransitionStart', () => {
				this.galleryThumbs.slideTo(this.galleryTop.activeIndex);
			});

			this.galleryThumbs.on('transitionStart', () => {
				this.galleryTop.slideTo(this.galleryThumbs.activeIndex);
			});

			this.galleryThumbs.on('click', (e) => {
				this.galleryTop.slideTo(e.clickedIndex)
				this.galleryThumbs.slideTo(e.clickedIndex)
			});
		}

		const outofstock = this.summaryDiv.querySelector('.out-of-stock');
		if (outofstock) {
			this.galleryTopEl.insertAdjacentHTML('beforeend', `
								<div class="z-[20] absolute top-hgap left-hgap border bg-black text-white text-sm px-hgap py-[3px] tracking-[1px] text-center uppercase">
									<span>Out of stock</span>
								</div>
							`);
		}

		this.galleryTop.init();

		if (this.photoSwipeEl) this.initPhotoSwipeFromDOM();

	}

	initPhotoSwipeFromDOM() {
		const lightbox = new this.PhotoSwipeLightbox({
			gallery: this.galleryTopEl.querySelector('.swiper-wrapper'),
			children: 'a',
			pswpModule: () => import('photoswipe')
		});

		lightbox.on("change", () => {
			if (this.galleryTop) this.galleryTop.slideTo(lightbox.pswp.currIndex, false);
		});

		lightbox.init();
	}

	variationChangeEvents() {

		$(".variations_form").on("show_variation", (_event, variation) => {

			let ajaxLoader = document.querySelector('.single-product__ajax-loader');
			if (!ajaxLoader.classList.contains('active')) ajaxLoader.classList.add('active');

			let varForm = this.summaryDiv.querySelector('.variations_form');
			let varProdPrice = '';
			if (varForm) varProdPrice = varForm.querySelector('.single_variation_wrap span.price');

			if (varForm && varProdPrice) this.summaryDiv.querySelector('p.price').innerHTML = varProdPrice.innerHTML;

			$.ajax({
				url: ajaxParamsSingleProd.url,
				type: 'POST',
				data: {
					action: 'ap_get_variation_image_and_gallery',
					_nonce: ajaxParamsSingleProd.nonce,
					variation_id: variation.variation_id,
					ajax_product_id: ajaxParamsSingleProd.ajax_product_id,
					use_main_gallery_only: ajaxParamsSingleProd.main_gallery_for_all
				},
				success: (res) => {
					this.galleryTop.destroy();
					if (this.galleryThumbsEl) this.galleryThumbs.destroy();
					$('.woo-prod-gal').replaceWith(res);
					this.galleryThumbsEl = document.querySelector('.gallery-thumbs');
					this.galleryTopEl = document.querySelector('.gallery-top');
					this.thumbsGalleryInit();
					this.galleryTopInit();
					const outofstock = this.summaryDiv.querySelector('.out-of-stock');
					if (outofstock) {
						this.galleryTopEl.insertAdjacentHTML('beforeend', `
								<div class="z-[20] absolute top-hgap left-hgap border bg-black text-white text-sm px-hgap py-[3px] tracking-[1px] text-center uppercase">
									<span>Out of stock</span>
								</div>
							`);
					}
					// this.galleryTop.lazy.load();
					// if (this.photoSwipeEl) this.initPhotoSwipeFromDOM();
					if (ajaxLoader.classList.contains('active')) ajaxLoader.classList.remove('active');
					window.scrollTo(0, 0);
					// ajaxLoader.classList.remove('loading');
				},
				error: (res) => {
					console.log("Sorry");
					console.log(res);
				}
			});

		});

	}

}

export default ProductGalleries;
