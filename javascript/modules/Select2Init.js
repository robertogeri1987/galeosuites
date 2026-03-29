import * as $ from 'jquery';

class Select2Init {

	constructor() {

		this.selectEls = document.querySelectorAll('select');

		if (this.selectEls.length > 0 && !ajaxParams?.isCheckout && !document.body.classList.contains('wp-admin')) {

			import('select2').then(_module => {

				for (const select of this.selectEls) {
					if (!select.dataset.wcvarselect) { // per questi select, viene fatta l'init da alpine nel modulo AlpineWCVariationsSelect
						$(select).select2({
							minimumResultsForSearch: 8,
						});
					}
				}

			});

		}

	}

}

export default Select2Init;
