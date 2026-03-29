class AlpineMenuItem {

	constructor() {

		window.Alpine.data('menuItem', () => ({
			open: false,
			navigationMode: '',
			initializedDesktop: false,
			initializedMobile: false,
			hovering: false,
			toggle() {
				this.open = !this.open
			},
			handleClick(e) {
				if (this.$refs.megaMenu && !this.open && this.navigationMode === 'mobile') {
					// follow link only if is open
					e.preventDefault();
					this.toggle();
				}
			},
			mobileEvents(gsap, Power3) {
				this.$watch('open', isOpen => {
					if (isOpen && this.navigationMode === 'mobile') {
						gsap.set(this.$refs.megaMenu, {
							autoAlpha: 1
						})
						gsap.to(this.$refs.megaMenu, {
							left: 0,
							duration: 1.2,
							ease: Power3.easeInOut
						})
					} else {
						if (this.navigationMode === 'mobile') {
							gsap.to(this.$refs.megaMenu, {
								left: '200%',
								duration: 1.2
							})
						}
					}
				})
			},
			deskTopEvents(gsap, Power3, hoverintent) {
				const navWidth = this.$el.closest('nav').offsetWidth;

				if (this.$el.offsetLeft + this.$refs.megaMenu.offsetWidth >= navWidth) {
					this.$refs.megaMenu.style.right = 0;
				} else {
					this.$refs.megaMenu.style.right = "auto";
				}

				hoverintent(
					this.$el,
					(e) => {
						this.hovering = true;
					},
					(e) => {
						this.hovering = false;
					}
				).options({ timeout: 300 });

				this.$watch('hovering', isHovering => {
					if (isHovering && this.navigationMode === 'desktop') {
						window.Alpine.store('header').menuOpen = true;

						gsap.to(this.$refs.megaMenu, {
							autoAlpha: 1,
							pointerEvents: 'auto',
							duration: 0.3,
							ease: Power3.inOut,
							overwrite: true,
							onComplete: (() => {
								window.Alpine.store('header').menuOpen = true;
							})
						})

					} else {
						if (this.navigationMode === 'desktop') {
							window.Alpine.store('header').menuOpen = false;

							gsap.to(this.$refs.megaMenu, {
								autoAlpha: 0,
								pointerEvents: 'none',
								duration: 0.3,
								ease: Power3.inOut,
								onComplete: (() => {
								})
							})

						}
					}
				})
			},
			init() {

				if (this.$refs.megaMenu) {

					import('gsap').then((
						{ default: gsap, Power3: Power3 }
					) => {
						import('hoverintent').then((
							{ default: hoverintent }
						) => {

							window.Alpine.effect(() => {
								this.navigationMode = window.Alpine.store('header').navigationMode;
								if (this.navigationMode === 'desktop') {
									gsap.set(this.$refs.megaMenu, {
										autoAlpha: 0,
										pointerEvents: 'none',
										duration: 0.4,
										ease: Power3.inOut,
									})
									if (!this.initializedDesktop) {
										this.deskTopEvents(gsap, Power3, hoverintent);
										this.initializedDesktop = true;
									}
								} else {
									if (this.navigationMode === 'mobile') {
										this.open = false;
										gsap.to(this.$refs.megaMenu, {
											pointerEvents: 'auto',
											duration: 1.2
										})
										if (!this.initializedMobile) {
											this.mobileEvents(gsap, Power3);
											this.initializedMobile = true;
										}
									}
								}
							})

						});
					});

				}

			}
		}))

	}

}

export default AlpineMenuItem;
