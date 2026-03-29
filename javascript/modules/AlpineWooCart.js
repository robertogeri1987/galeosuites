
import * as $ from 'jquery';
import AlpineWooNotices from './AlpineWooNotices';
class AlpineWooCart {

	constructor() {

		window.Alpine.data('woocart', () => ({
			couponText: '',
			coupons: [],
			couponsRemove: [],
			loadingCart: false,
			initialized: false,
			getElements() {
				this.coupons = document.querySelectorAll('[data-coupon]');
				this.couponsRemove = document.querySelectorAll('[data-coupon-remove]');
			},
			events() {
				if (this.couponsRemove.length > 0) {
					for (let i = 0; i < this.couponsRemove.length; i++) {
						this.couponsRemove[i].addEventListener('click', () => {
							let removeBtn = document.querySelector('[data-coupon="' + this.couponsRemove[i].dataset.remove + '"]');
							if (removeBtn) {
								removeBtn.click();
							}
						})
					}
				}
			},
			rebuildElements() {
				// document.querySelector('[data-fake-coupons-container]').innerHTML = '';
				// if (this.coupons.length > 0) {
				// 	for (const c of this.coupons) {
				// 		const btn = document.createElement('button');
				// 		btn.innerHTML = `
				// 		<button data-remove="${c.dataset.coupon}" class="text-sm font-semibold bg-tortora leading-[14.4px] rounded-full px-gap py-[2px] flex items-center" data-coupon-remove type="button">${c.dataset.coupon} <svg class="h-[7px] w-[7px] stroke-2 stroke-current fill-current ml-gap"><title></title><use xlink:href="#close-outline"></use></svg></button>
				// 	`;
				// 		document.querySelector('[data-fake-coupons-container]').insertAdjacentElement('beforeend', btn);
				// 	}
				// }
			},
			restartAll() {
				this.getElements();
				this.rebuildElements();
				this.getElements();
				this.events();
				new AlpineWooNotices();
				$(document.body).trigger('wc_fragment_refresh');
			},
			applyCoupon() {
				const couponBtn = document.querySelector('[data-real-coupon-btn]');
				if (couponBtn) couponBtn.click();
				this.couponText = '';
			},
			init() {
				const woocommerce_form = $('[data-wc-cart]');
				this.restartAll();
				woocommerce_form.on('submit', (e) => {
					e.preventDefault();
					this.loadingCart = true;
					$('<input />')
						.attr('type', 'hidden')
						.attr('name', 'update_cart')
						.attr('value', 'Update Cart')
						.appendTo(woocommerce_form);

					$.ajax({
						type: woocommerce_form.attr('method'),
						url: woocommerce_form.attr('action'),
						data: woocommerce_form.serialize(),
						dataType: 'html',
						success: () => {
							this.restartAll();
						}
					});
				})

			}
		}));
	}

}

export default AlpineWooCart;
