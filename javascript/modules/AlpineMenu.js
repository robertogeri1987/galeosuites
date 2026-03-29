
class AlpineMenu {

	constructor() {

		window.Alpine.data('primaryMenu', () => ({
			initialized: false,
			navigationMode: '',
			elementsMoved: false,
			mobileEvents(mobileTl, Power2) {

				if (this.navigationMode === 'mobile') {

					mobileTl.set(this.$el, {
						autoAlpha: 0,
						top: '-100%',
						pointerEvents: 'none'
					});

					mobileTl.to(this.$el, {
						autoAlpha: 1,
						pointerEvents: 'all',
						top: '0',
						duration: 0.7
					})

					mobileTl.reversed(true);
				}
			},
			deskTopEvents(gsap, Power2) {
				console.log('deskTopEvents');
			},
			restoreElements(fxCols) {
				if (this.elementsMoved) {
					for (const col of fxCols) {
						const nextSibling = col.nextElementSibling;
						const items = col.querySelectorAll('li');
						const images = nextSibling.querySelectorAll('img');
						if (nextSibling) {
							const container = nextSibling.querySelector('figure');
							for (let i = 1; i < images.length; i++) {
								items[i].querySelector('figure').insertAdjacentElement('beforeend', images[i])
							}
						}
					}
					this.elementsMoved = false;
				}
			},
			moveElements(fxCols) {
				for (const col of fxCols) {
					const nextSibling = col.nextElementSibling;
					const items = col.querySelectorAll('li');
					if (nextSibling) {
						const container = nextSibling.querySelector('figure');
						if (container) {
							for (let i = 1; i < items.length; i++) {
								const img = items[i].querySelector('img');
								if (img) {
									container.insertAdjacentElement('beforeend', img);
									items[i].addEventListener('mouseenter', () => {
										if (!img.classList.contains('desk:opacity-100')) img.classList.add('desk:opacity-100');
										img.classList.remove('desk:opacity-0');
									});
									items[i].addEventListener('mouseleave', () => {
										if (!img.classList.contains('desk:opacity-0')) img.classList.add('desk:opacity-0');
										img.classList.remove('desk:opacity-100');
									});
								}

							}
						}
					}
				}
				this.elementsMoved = true;
			},
			init() {

				import('gsap').then((
					{ default: gsap, Power2: Power2 }
				) => {
					const mobileTl = gsap.timeline();
					const fxCols = this.$el.querySelectorAll('[data-hover-fx-col]');

					window.Alpine.effect(() => {
						this.navigationMode = window.Alpine.store('header').navigationMode;
						if (this.navigationMode === 'mobile') {
							window.Alpine.store('header').closeMenu();
							mobileTl.reversed(true)
							if (!this.initialized) {
								this.mobileEvents(mobileTl, Power2);
								this.initialized = true;
							}
							this.restoreElements(fxCols);

						} else {
							window.Alpine.store('header').closeMenu();
							mobileTl.reversed(false)
							this.moveElements(fxCols);
						}
					})

					window.Alpine.effect(() => {
						const menuOpen = window.Alpine.store('header').menuOpen;
						if (this.navigationMode === 'mobile') {
							if (menuOpen) {
								mobileTl.reversed(false)
							} else {
								mobileTl.reversed(true)
							}
						}
					})
				});

			}
		}))

	}

}

export default AlpineMenu;
