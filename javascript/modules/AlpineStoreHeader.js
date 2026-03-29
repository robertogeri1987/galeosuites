
class AlpineStoreHeader {

	constructor() {

		window.Alpine.store('header', {
			menuOpen: false,
			navigationMode: '',
			hasScrolled: false,
			isScrolling: false,
			isWhite: false,
			toggleMenuOpen() {
				this.menuOpen = !this.menuOpen;
			},
			openMenu() {
				this.menuOpen = true;
			},
			closeMenu() {
				this.menuOpen = false;
			},
			setNavMode(mode) {
				this.navigationMode = mode;
			},
			handleScroll() {
				const onScrollStop = callback => {
					let isScrolling;
					window.addEventListener(
						'scroll',
						e => {
							clearTimeout(isScrolling);
							this.isScrolling = true;
							isScrolling = setTimeout(() => {
								callback();
							}, 150);
						},
						false
					);
				}
				onScrollStop(() => {
					this.isScrolling = false;
				})
				setTimeout(() => {
					if (window.scrollY > 50) {
						this.hasScrolled = true;
					} else {
						this.hasScrolled = false;
					}
				}, 50);
			},

			init() {
				window.addEventListener("scroll", this.handleScroll.bind(this));
				if (window.scrollY > 50) {
					this.hasScrolled = true;
				}
			},
		});

	}

}

export default AlpineStoreHeader;
