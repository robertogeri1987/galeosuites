
import * as $ from 'jquery';
class AlpineWooCheckout {

	constructor() {

		window.Alpine.data('woocheckout', () => ({
			couponText: '',
			coupons: [],
			couponsRemove: [],
			shippingOptionsContainer: document.querySelector('[data-shipping-options-container]'),
			realShippingMethods: [],
			activeShippingIndex: -1,
			realShipDifferentCheck: document.querySelector('#ship-to-different-address'),
			shipDifferent: false,
			gateway: '',
			applyCoupon() {
				const couponBtn = document.querySelector('[data-real-coupon-btn]');
				if (couponBtn) couponBtn.click();
				this.couponText = '';
			},
			setActiveShipping(index) {
				if (this?.realShippingMethods?.[index]) this.realShippingMethods[index]?.querySelector('input')?.click();
			},
			setShipDifferent(val) {
				if (this?.realShipDifferentCheck?.querySelector('input')) {
					if (val !== this.shipDifferent) {
						this.shipDifferent = val;
						this.realShipDifferentCheck.querySelector('input').click();
					}
				}
			},
			getElements() {
				this.coupons = document.querySelectorAll('[data-coupon]');
				this.couponsRemove = document.querySelectorAll('[data-coupon-remove]');
				this.shippingOptionsContainer = document.querySelector('[data-shipping-options-container]');
				this.realShipDifferentCheck = document.querySelector('#ship-to-different-address');

				const shippingMethodsContainer = document.querySelector('#shipping_method');
				if (shippingMethodsContainer) this.realShippingMethods = shippingMethodsContainer.querySelectorAll('li');
				// this.gateway = document.querySelector('input[name="payment_method"]:checked')?.value;
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
				document.querySelector('[data-fake-coupons-container]').innerHTML = '';
				this.shippingOptionsContainer.innerHTML = '';
				this.shipDifferent = this?.realShipDifferentCheck?.querySelector('input')?.checked;

				for (let i = 0; i < this.realShippingMethods.length; i++) {
					if (this.realShippingMethods[i].querySelector('input').checked) {
						this.activeShippingIndex = i;
					}
				}

				if (this.coupons.length > 0) {
					for (const c of this.coupons) {
						const btn = document.createElement('button');
						btn.innerHTML = `
						<button data-remove="${c.dataset.coupon}" class="text-sm font-semibold bg-white leading-[14.4px] px-gap py-[2px] flex items-center" data-coupon-remove type="button">${c.dataset.coupon} <svg class="h-[10px] w-[10px] stroke-2 stroke-current fill-current ml-hgap"><title></title><use xlink:href="#close-outline"></use></svg></button>
					`;
						document.querySelector('[data-fake-coupons-container]').insertAdjacentElement('beforeend', btn);
					}
				}

				if (this.realShippingMethods.length > 0) {
					let index = 0;
					for (const m of this.realShippingMethods) {
						const label = m.querySelector('label');
						const desc = m.querySelector('[data-shipping-text]');
						let labelTxt = '';
						let price = ajaxParams?.freeShippingPriceTxt;
						if (label) {
							labelTxt = label.textContent.split(':')[0];
							if (label.textContent.split(':')[1]) price = label.textContent.split(':')[1];
						}
						let descText = '';
						if (desc) {
							descText = desc.textContent;
						}
						const btn = document.createElement('button');
						btn.type = 'button';
						btn.innerHTML = `
							<div class="bg-white p-gap flex items-center justify-start border transition-all duration-500" :class="${index} === activeShippingIndex ? 'border-primary' : 'border-white cursor-pointer'" @click="setActiveShipping(${index})">
								<div class="rounded-full min-w-[20px] w-[20px] h-[20px] bg-light flex items-center justify-center mr-gap">
									<div class="rounded-full min-w-[10px] w-[10px] h-[10px]" :class="${index} === activeShippingIndex ? 'bg-dark' : 'bg-light'"></div>
								</div>
								<div class="flex-1 flex flex-col items-start justify-center gap-hgap">
									<span class="text-sm">${labelTxt}</span>
									<span class="text-sm font-semibold">${price}</span>
									${descText ? `<span class="text-sm">${descText}</span>` : ``}
								</div>
							</div>
						`;
						this.shippingOptionsContainer.insertAdjacentElement('beforeend', btn);
						index++;
					}
				} else {
					this.shippingOptionsContainer.insertAdjacentHTML('beforeend', `<p class="text-left text-alert">${ajaxParams.noShippingAvailable}</p>`);
				}
			},
			restartAll() {
				this.getElements();
				this.rebuildElements();
				this.getElements();
				this.events();
			},
			init() {
				this.restartAll();

				$('body').on('updated_checkout applied_coupon_in_checkout removed_coupon_in_checkout country_to_state_changed', () => {
					this.restartAll();
				});

				const gatewayRadios = document.querySelectorAll('input[name="payment_method"]');
				if (gatewayRadios.length > 0) {
					this.gateway = gatewayRadios[0].value;
				}
			}
		}))

	}

}

export default AlpineWooCheckout;
