
class Blueprint {

	constructor() {

		this.els = document.querySelectorAll('[data-blueprint]');

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

}

export default Blueprint;
