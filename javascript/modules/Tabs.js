import * as $ from 'jquery';

class Tabs {

	constructor() {
		this.tabsContainers = document.querySelectorAll('.tabs__container');
		if (this.tabsContainers.length > 0) this.events();
	}

	events() {

		for (let b = 0; b < this.tabsContainers.length; b++) {

			let tabs = this.tabsContainers[b].querySelectorAll('.tabs__toggle');
			let tabbedContent = this.tabsContainers[b].querySelectorAll('.tabs__tab');

			for (let i = 0; i < tabs.length; i++) {

				tabs[i].addEventListener('click', () => {

					for (let b = 0; b < tabs.length; b++) {
						if (tabs[b] != tabs[i]) {
							tabs[b].classList.remove('active');
						}
					}
					if (!tabs[i].classList.contains('active')) {
						tabs[i].classList.add('active');
					}
					for (let count = 0; count < tabbedContent.length; count++) {
						if (tabbedContent[count] != tabs[i]) {
							$(tabbedContent[count]).hide(0).removeClass('active');
						}
					}
					if (!tabbedContent[i].classList.contains('active')) {
						$(tabbedContent[i]).fadeIn(1000).addClass('active');
					}

				})

			}
		}

	}

}

export default Tabs;
