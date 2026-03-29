class OnScroll {

	constructor() {

		this.els = document.querySelectorAll('[data-gs-reveal]');

		if (this.els.length > 0) {

			import('gsap').then((
				{ default: gsap, Linear: Linear, Elastic: Elastic }
			) => {

				import('gsap/ScrollTrigger').then(({ default: ScrollTrigger }) => {

					gsap.registerPlugin(ScrollTrigger);

					import('imagesloaded').then(({ default: ImagesLoaded }) => {

						for (const box of this.els) {
							ImagesLoaded(box, () => {
								ScrollTrigger.create({
									trigger: box,
									offsetY: 100,
									end: "bottom bottom",
									once: true,
									onEnter: self => {
										this.animateFrom(self, box, gsap, Linear, Elastic);
									}
								});
							})

						}
					});

				});

			});

		}

	}

	animateFrom(self, box, gsap, Linear, Elastic) {

		let x = 0, y = 0, autoAlpha = 0, rotation = 0, duration = 1, ease = Linear.easeNone;

		if (box.dataset.gsReveal === "reveal_fromTop") { y = -50; }
		if (box.dataset.gsReveal === "reveal_fromBottom") { y = 50; }
		if (box.dataset.gsReveal === "reveal_fromRight") { x = 200; }
		if (box.dataset.gsReveal === "reveal_fromLeft") { x = -200; }
		if (box.dataset.gsReveal === "rotate_fromLeft") { duration = 0.5; autoAlpha = 1; y = -200; rotation = -90 }
		if (box.dataset.gsReveal === "rotate_fromRight") { duration = 0.5; autoAlpha = 1; y = 200; rotation = 90 }
		if (box.dataset.gsReveal === "fromTop") { duration = 0.5; autoAlpha = 1; x = 0; y = -200; }
		if (box.dataset.gsReveal === "bounce_fromTop") {
			duration = 1.5;
			autoAlpha = 1;
			x = 0;
			y = -200;
			ease = Elastic.easeOut.config(1, 0.3);
		}

		gsap.set(box, { autoAlpha: autoAlpha, y: y, x: x, rotation: rotation });

		const anim = gsap.to(box, {
			ease: ease,
			duration: duration,
			delay: 0.5,
			autoAlpha: 1,
			y: 0,
			x: 0,
			rotation: 0,
			paused: true
		});

		self.progress === 1 ? anim.progress(1) : anim.play();

	}

}

export default OnScroll;
