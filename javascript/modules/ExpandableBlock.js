class ExpandableBlock {

	constructor() {

		this.els = document.querySelectorAll('.exp');

		if (this.els.length > 0) {
			import('gsap').then((
				{ default: gsap, Linear: Linear, Elastic: Elastic }
			) => {

				this.gsap = gsap;
				this.Linear = Linear;
				this.Elastic = Elastic;

				for (const el of this.els) {
					this.init(el);
				}

			});

		}

	}

	init(el) {

		const toggler = el.querySelector('.exp__toggler');
		const arrow = toggler.querySelector('.exp-arrow');
		const content = el.querySelector('.exp__content')

		const tl = this.gsap.timeline();
		this.gsap.set(arrow, {
			'rotate': '0deg'
		})
		this.gsap.set(content, {
			'height': "0"
		})
		tl.to(content, {
			height: 'auto',
			duration: 0.4
		}, 0)
		tl.to(arrow, {
			rotate: '45deg',
			duration: 0.4
		}, 0)
		tl.reversed(true);

		toggler.addEventListener('click', () => {
			tl.reversed(!tl.reversed())
		});

	}

}

export default ExpandableBlock;
