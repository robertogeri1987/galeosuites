import * as $ from 'jquery';

class PredictiveSearch {

	constructor() {
		this.searchFormContainer = document.querySelector('[data-predictive-search-container');
		this.isOverlayOpen = false;
		this.isSpinnerVisible = false;
		this.previousValue;
		this.typingTimer;

		if (this.searchFormContainer) {
			this.searchField = this.searchFormContainer.querySelector('[data-predictive-search-input]');
			this.resultsDiv = this.searchFormContainer.querySelector('[data-predictive-search-results]');
			this.events();
		}

	}

	events() {
		this.searchField.addEventListener('keyup', this.typingLogic.bind(this));
		document.addEventListener('keydown', this.keyPressDispatcher.bind(this));
		document.addEventListener('click', this.clickOutside.bind(this));
	}

	typingLogic() {
		if (this.searchField.value != this.previousValue && this.searchField.value.length > 2) {
			clearTimeout(this.typingTimer);

			if (this.searchField.value) {
				if (!this.isSpinnerVisible) {
					this.resultsDiv.innerHTML = searchParams.loader;
					this.isSpinnerVisible = true;
				}
				this.openOverlay();
				this.typingTimer = setTimeout(this.getResults.bind(this), 400);
			} else {
				this.closeOverlay();
				this.resultsDiv.innerHTML = '';
				this.isSpinnerVisible = false;
			}
		}
		this.previousValue = this.searchField.value;

		if (this.searchField.value.length === 0) {
			this.closeOverlay();
		}
	}

	getResults() {
		let searchUrl = searchParams.root_url + '/wp-json/searchroute/v1/search?term=' + this.searchField.value;

		$.getJSON(searchUrl, (results) => {

			let noResults = true;

			this.resultsDiv.innerHTML = '';

			if (results.generalInfo.length > 0) {
				noResults = false;
				this.resultsDiv.innerHTML += `<li data-predictive-search-title class="font-bold uppercase mt-hgap mb-hhgap">${searchParams.generalInfoTitle}</li>`;

				for (const listItem of results.generalInfo) {
					this.resultsDiv.innerHTML += this.buildListItem(listItem);
				}
			}
			if (results.products.length > 0) {
				noResults = false;
				this.resultsDiv.innerHTML += `<li data-predictive-search-title class="font-bold uppercase mt-hgap mb-hhgap">${searchParams.productsTitle}</li>`;

				for (const listItem of results.products) {
					this.resultsDiv.innerHTML += this.buildListItem(listItem);
				}
			}
			if (results.productCategories.length > 0) {
				noResults = false;
				this.resultsDiv.innerHTML += `<li data-predictive-search-title class="font-bold uppercase mt-hgap mb-hhgap">${searchParams.productCategoriesTitle}</li>`;

				for (const listItem of results.productCategories) {
					this.resultsDiv.innerHTML += this.buildListItem(listItem);
				}
			}

			this.isSpinnerVisible = false;

			if (noResults) this.resultsDiv.innerHTML = searchParams.noResultsMessage;

		});
	}

	buildListItem(item) {
		return `
            <li data-predictive-search-item class="w-full py-hhgap min-h-[50px] transition-all duration-500 flex">
                <a data-predictive-search-link class="flex items-center min-h-full w-full no-underline hover:text-primary" href="${item.permalink}">${item.image ? item.image : ''}<span>${item.title}</span></a>
            </li>`;
	}

	keyPressDispatcher(e) {

		if (e.keyCode == 27 && this.isOverlayOpen) {
			this.closeOverlay();
		}

	}

	clickOutside(e) {
		let targetResults = e.target.closest('[data-predictive-search-container]');
		if (!targetResults) this.closeOverlay();
	}

	openOverlay() {
		this.resultsDiv.classList.add('active');
		document.body.classList.add('body-no-scroll-mobile');
		setTimeout(() => this.searchField.focus(), 301);
		this.isOverlayOpen = true;
		return false;
	}

	closeOverlay() {
		this.resultsDiv.classList.remove('active');
		document.body.classList.remove('body-no-scroll-mobile');
		this.searchField.value = '';
		this.isOverlayOpen = false;
	}

}

export default PredictiveSearch;
