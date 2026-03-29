import * as $ from 'jquery';

class AlpineWCVariationsSelect {

	constructor() {

		window.Alpine.data('wcvar', () => ({
			active: false,
			cartHiddenInput: document.querySelector('input.variation_id'),
			setActive(el) {
				this.active = el.dataset.index;
				this.cartHiddenInput.value = el.dataset.varid;
				const attrs = el.dataset.attrname.split(',');
				const vals = el.dataset.name.split('/');
				for (let i = 0; i < attrs.length; i++) {
					const selectVars = document.querySelector(`select[name="${attrs[i]}"]`);
					selectVars.value = vals[i].trim();
					$(selectVars).trigger('change');
				}
			},
			init() {
				if (this.$refs.selectRef) {
					import('select2').then(_module => {
						$(this.$refs.selectRef).select2({
							allowClear: true
						});
						$(this.$refs.selectRef).on('select2:select', (e) => {
							const data = e?.params?.data;
							this.setActive(data?.element)
						})
						this.setActive($(this.$refs.selectRef).select2().find(":selected")?.[0])
					});
				}
			}
		}))

	}

}

export default AlpineWCVariationsSelect;
