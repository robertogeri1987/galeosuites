
import * as $ from 'jquery';
class AlpineWooNotices {

	constructor() {

		this.els = document.querySelectorAll('.notice');
		this.gsap = null;

		if (this.els.length > 0) {

			import('gsap').then((
				{ default: gsap }
			) => {

				this.gsap = gsap;

				import('gsap/ScrollToPlugin').then(({ default: ScrollToPlugin }) => {
					this.gsap.registerPlugin(ScrollToPlugin);

					for (const el of this.els) {
						this.setElements(el);
						this.inlineNotices();
						this.events();
					}

					window.Alpine.data('wcnotice', () => ({
						dismissed: false,
						removeEl() {
							this.dismissed = true;
							setTimeout(() => {
								this.$root.remove();
							}, 500)
						}
					}));

				});
			});

		}

	}

	restart() {
		this.els = document.querySelectorAll('.notice');
		for (const el of this.els) {
			this.setWrapper(el);
			this.setElements(el);
			this.inlineNotices();
		}
	}

	setWrapper(el) {
		//Make sure the notice is properly wrapped
		if (el.parentElement.classList.contains('woocommerce-notices-wrapper') || el.parentElement.classList.contains('woocommerce-NoticeGroup')) return;

		const wrapper = document.createElement('div');
		wrapper.classList.add('woocommerce-notices-wrapper');
		el.parentElement.insertBefore(wrapper, el);
		wrapper.appendChild(el);
	}

	inlineNotices() {
		const requiredFields = document.querySelectorAll('.validate-required');
		let hasErrors = false;

		for (const field of requiredFields) {
			const label = field.querySelector('label');

			if (!label) continue;
			const err = label.querySelector('[data-inlineerr]');

			if (field.classList.contains('woocommerce-invalid')) {
				if (!err) label.insertAdjacentHTML('beforeend', `<span data-inlineerr class="block w-full pr-gap float-left my-hhgap text-alert">${ajaxParams?.genericCheckoutFieldError}</span>`);
				hasErrors = true;
			} else {
				if (err) err.remove();
			}

			if (hasErrors) {
				const firstError = document.querySelector('.woocommerce-invalid-required-field');
				if (firstError) {
					this.gsap.to(window, {
						duration: 1,
						scrollTo: { y: firstError, offsetY: 200 }
					});
				}
			}
		}
	}

	setElements(el) {
		el.setAttribute('x-data', `wcnotice`);
		el.classList.remove('hidden')
		el.classList.add('transition-all', 'duration-500');
		el.setAttribute(':class', `dismissed ? 'opacity-0' : 'opacity-1'`);

		if (!el.querySelector('[data-remove-notice]')) {
			el.insertAdjacentHTML('beforeend', `
				<button type="button" data-remove-notice class="pl-gap ml-auto notice-remove-btn" @click="removeEl()">
					<svg class="rem:w-[16px] rem:h-[16px] fill-none stroke-current transition-all duration-400"><title>Chiudi</title><use xlink:href="#close-outline"></use></svg>
				</button>
			`)
		}
	}

	events() {
		$('body').on('checkout_error applied_coupon_in_checkout removed_coupon_in_checkout', () => {
			this.restart();
		});
	}

}

export default AlpineWooNotices;
