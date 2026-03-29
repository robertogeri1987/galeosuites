
import 'js-datepicker/dist/datepicker.min.css';
class DatePicker {

	constructor() {

		this.els = document.querySelectorAll('[data-datepicker]');

		if (this.els.length > 0) {
			import('js-datepicker').then((
				{ default: datepicker }
			) => {

				this.datepicker = datepicker

				for (const el of this.els) {
					const pickerEl = el.querySelector('[data-datepickerel]');
					const button = el.querySelector('[data-request-button]');
					this.init(pickerEl, button);
				}

			});

		}

	}

	init(pickerEl, button) {
		this.datepicker(pickerEl, {
			alwaysShow: true,
			onSelect: (_instance, date) => {
				if (!date) {
					button.disabled = true;
					return;
				}
				const dateString = date.getYear() + 1900 + '-' + (date.getMonth() + 1) + '-' + date.getDate();
				button.disabled = false;
				button.addEventListener('click', () => {
					const href = `mailto:?subject=${encodeURIComponent(button.dataset.subject + dateString)}`;
					window.location.href = href;
				});
			}
		})
	}

}

export default DatePicker;
