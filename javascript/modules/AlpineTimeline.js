
class AlpineTimeline {

	constructor() {

		window.Alpine.data('timeline', () => ({
			active: null,
			init() {
				this.active = this.$root.dataset.firstActive
				let icons = this.$root.querySelectorAll("[data-anno]");
				let contents = this.$root.querySelectorAll('[data-content]');

				window.Alpine.effect(() => {
					this.navigationMode = window.Alpine.store('header').navigationMode;
					if (this.navigationMode === 'mobile') {
						this.$root.style.height = `auto`;
					} else {
						let maxHeight = this.$refs.timeline.scrollHeight;
						console.log(this.$refs.timeline);

						for (const c of contents) {
							let h = c.getBoundingClientRect().height;
							if (h > maxHeight) {
								maxHeight = h;
							}
						}

						this.$root.style.height = `${maxHeight}px`;
					}
				})

				const focus = (elem, index) => {
					let previous = index - 1;
					let previous1 = index - 2;
					let next = index + 1;
					let next2 = index + 2;

					if (previous == -1) {
						elem.style.transform = "scale(1.5)  translateY(-10px)";
					} else if (next == icons.length) {
						elem.style.transform = "scale(1.5)  translateY(-10px)";
					} else {
						elem.style.transform = "scale(1.5)  translateY(-10px)";
						if (icons?.[previous]?.style?.transform) icons[previous].style.transform = "scale(1.2) translateY(-6px)";
						if (icons?.[previous1]?.style?.transform) icons[previous1].style.transform = "scale(1.1)";
						if (icons?.[next]?.style?.transform) icons[next].style.transform = "scale(1.2) translateY(-6px)";
						if (icons?.[next2]?.style?.transform) icons[next2].style.transform = "scale(1.1)";
					}
				}

				icons.forEach((item, index) => {
					item.addEventListener("mouseover", (e) => {
						focus(item, index);
						this.active = index + 1;
					});
					item.addEventListener("click", (e) => {
						if (window.Alpine.store('header').navigationMode === 'mobile') this.active = index + 1;
					});
					item.addEventListener("mouseleave", (e) => {
						icons.forEach((item) => {
							item.style.transform = "scale(1)  translateY(0px)";
						});
					});
				});
			}
		}))

	}

}

export default AlpineTimeline;
