import { _isDesktop } from './Utilities.js';
import Select2Init from './Select2Init.js';
import Wishlist from './Wishlist.js';
import * as $ from 'jquery';

class FlexibleArchiveFilters {

	constructor() {

		this.sidebar = document.querySelector('.sidebar-primary');
		this.horizontalSidebar = document.querySelector('.sidebar-secondary');

		if (this.sidebar) this.allSidebarFilters = this.sidebar.querySelectorAll('.archive-filters--vertical');

		if (this.horizontalSidebar) {
			this.allHorizontalFilters = this.horizontalSidebar.querySelectorAll('.archive-filters__col');
			this.horizontalFiltersContainer = this.horizontalSidebar.querySelector('.archive-filters__row');
			this.allFiltersClose = this.horizontalFiltersContainer.querySelector('.archive-filters__row-close');
			this.mobileMoveFiltersHook = this.horizontalFiltersContainer.querySelector('.screen-reader-text');
			this.filterToggler = this.horizontalSidebar.querySelector('.archive-filters__toggler');
		}

		this.allFilters = document.querySelectorAll('.archive-filters');
		this.allMultifilters = document.querySelectorAll('.archive-filters__ul--multifilter');
		this.currentUrl = document.querySelector('.currenturldiv');
		if (this.currentUrl) this.currentUrl = this.currentUrl.dataset.currenturl;
		this.ajaxLoader = document.querySelector('.archive-filters__ajax-loader');

		this.elementsMoved = false;

		if (this.sidebar || this.horizontalSidebar) {
			this.eventsDispatcher();
			this.moveEvents();
		}

	}

	eventsDispatcher() {

		if (this.allMultifilters.length > 0) this.multiFilterEvents();
		if (this.allFilters.length > 0) this.allFilterEvents();

		if (this.filterToggler) {
			this.filterToggler.addEventListener('click', () => {
				this.horizontalFiltersContainer.classList.add('active');
			});
		}

		if (this.allFiltersClose) {
			this.allFiltersClose.addEventListener('click', () => {
				this.horizontalFiltersContainer.classList.remove('active');
			})
		}

	}

	allFilterEvents() {

		for (const filter of this.allFilters) {
			this.expandEvents(filter);

			if (coreParams.isAjax) {
				if (!filter.querySelector('.archive-filters__ul--multifilter')) {
					const thisFilterLinks = filter.querySelectorAll('.archive-filters__link');
					for (const link of thisFilterLinks) {
						link.addEventListener('click', (e) => {
							e.preventDefault();
							this.ajaxLoad(link.href);
						});
					}
				}
			}

		}

	}

	multiFilterEvents() {

		for (const multiFilter of this.allMultifilters) {

			const multiFilterLinks = multiFilter.querySelectorAll('.archive-filters__link');
			const filterButton = multiFilter.querySelector('.archive-filters__multifilter-button');

			if (filterButton) this.filterButtonsEvents(filterButton);

			const originalActiveValues = [];

			for (const multiFilterLink of multiFilterLinks) {
				if (multiFilterLink.classList.contains('active')) {
					originalActiveValues.push(multiFilterLink.dataset.mfvalue);
				}
			}

			for (const multiFilterLink of multiFilterLinks) {
				multiFilterLink.addEventListener("click", e => {
					e.preventDefault();
					e.stopPropagation();
					this.multiFilterClickEvent(
						multiFilterLink, multiFilterLinks, filterButton, originalActiveValues
					);
				});
			}

		}

	}

	filterButtonsEvents(filterButton) {

		filterButton.addEventListener('click', () => {

			if (coreParams.isAjax) {
				this.ajaxLoad(filterButton.dataset.mfurl);
			} else {
				window.location.href = filterButton.dataset.mfurl;
			}

		});

	}

	multiFilterClickEvent(multiFilterLink, multiFilterLinks, filterButton, originalActiveValues) {

		multiFilterLink.classList.contains('active') ? multiFilterLink.classList.remove('active') : multiFilterLink.classList.add('active');

		// Get all active elements
		const allFilterActiveValues = [];

		for (const multiFilterLink of multiFilterLinks) {
			if (multiFilterLink.classList.contains('active')) {
				allFilterActiveValues.push(multiFilterLink.dataset.mfvalue);
			}
		}

		if (filterButton) {

			// If filter remove/add restore the initial state of the filter, do not enable the button
			if (allFilterActiveValues.join() === originalActiveValues.join()) {
				if (!filterButton.classList.contains('disabled')) filterButton.classList.add('disabled');
			} else {
				this.buildUrl(multiFilterLink, allFilterActiveValues, filterButton);
			}

		} else {
			this.buildUrl(multiFilterLink, allFilterActiveValues, filterButton);
		}

	}

	buildUrl(multiFilterLink, allFilterActiveValues, filterButton) {

		let urlExploded = this.currentUrl.split('?');
		let urlBefore = urlExploded[0];

		let urlNewParams = [];
		let updatedLink = '';
		let updated = false;

		if (urlExploded[1] !== undefined) { // if there are other url params

			let urlParams = urlExploded[1].split('&');

			for (const param of urlParams) {

				let paramExploded = param.split('=');
				let paramTerm = paramExploded[0];
				let paramValue = paramExploded[1];

				// Update only the current param
				if (multiFilterLink.dataset.mfterm === paramTerm) {

					if (allFilterActiveValues.length > 0) {
						urlNewParams[coreParams.index[multiFilterLink.dataset.mfterm][0]] = `${multiFilterLink.dataset.mfterm}=${allFilterActiveValues.join(',')}`;
					} else {
						urlNewParams[coreParams.index[multiFilterLink.dataset.mfterm][0]] = '';
					}

					if (filterButton) {
						if (filterButton.classList.contains('disabled')) filterButton.classList.remove('disabled');
					}

					updated = true;

				} else if (coreParams.index[paramTerm] !== undefined) { // If it's not the current param, just rebuild the url

					urlNewParams[coreParams.index[paramTerm][0]] = `${paramTerm}=${paramValue}`;

					if (filterButton) {
						if (filterButton.classList.contains('disabled')) filterButton.classList.remove('disabled');
					}

				}

			}

			// If the term wasn't in the query params of the url, add it
			if (!updated) urlNewParams[coreParams.index[multiFilterLink.dataset.mfterm][0]] = `${multiFilterLink.dataset.mfterm}=${allFilterActiveValues.join(',')}`;

			// Remove empty elements from array
			let filteredParams = urlNewParams.filter(el => el != null); // Remove null entries from array
			filteredParams = filteredParams.filter(el => el != ''); // Remove empty string entries from array

			// Udpate the link
			filteredParams.length > 0 ? updatedLink = `${urlBefore}/?${filteredParams.join('&')}` : updatedLink = urlBefore;

			if (filterButton) {
				if (filterButton.classList.contains('disabled')) filterButton.classList.remove('disabled');
			}

			if (coreParams.isAjax) {
				(filterButton) ? filterButton.dataset.mfurl = updatedLink : this.ajaxLoad(updatedLink);
			} else {
				(filterButton) ? filterButton.dataset.mfurl = updatedLink : window.location.href = updatedLink;
			}

			return;

		}

		// If no url params were present
		if (allFilterActiveValues.length > 0) {

			if (filterButton) {
				if (filterButton.classList.contains('disabled')) filterButton.classList.remove('disabled');
			}
			updatedLink = `${urlBefore}/?${multiFilterLink.dataset.mfterm}=${allFilterActiveValues.join(',')}`;

		} else {

			if (filterButton) {
				if (!filterButton.classList.contains('disabled')) filterButton.classList.add('disabled');
			}
			updatedLink = '';

		}

		(filterButton) ? filterButton.dataset.mfurl = updatedLink : window.location.href = updatedLink;

	}

	expandEvents(filter) {

		filter.addEventListener('click', (e) => {
			if (e.target.classList.contains('widget-title')) {
				e.preventDefault();
				filter.classList.contains('expanded') ? filter.classList.remove('expanded') : filter.classList.add('expanded');
			}
		});
		filter.querySelector('.archive-filters__back-button').addEventListener('click', () => {
			filter.classList.remove('expanded');
		});

	}

	ajaxLoad(filterUrl) {

		this.ajaxLoader.classList.add('loading');

		fetch(filterUrl, {
			method: 'GET'
		}).then(async res => {

			if (res.status >= 200 && res.status < 300) { // The API call was successful!
				return res.text();
			} else {
				const errData = await res.json();
				console.log(errData);
				throw new Error('Something went wrong - server side');
			}

		}).then(htmlString => {
			this.ajaxLoader.classList.remove('loading');
			// Convert the HTML string into a document object
			let parser = new DOMParser();
			let bodyHtml = parser.parseFromString(htmlString, 'text/html').body;
			let headHtml = parser.parseFromString(htmlString, 'text/html').head;

			this.rebuildDOM(bodyHtml, headHtml, filterUrl);
			this.reloadEvents();

			import('vanilla-lazyload').then((
				{ default: LazyLoad }
			) => {
				new LazyLoad({
					elements_selector: ".lazyload"
				});
			});

		}).catch(err => {
			this.ajaxLoader.classList.remove('loading');
			// There was an error
			console.warn('Something went wrong.', err);
		});

	}

	rebuildDOM(bodyHtml, headHtml, filterUrl) {
		this.allFilters = document.querySelectorAll('.archive-filters');
		document.querySelector('#content').innerHTML = bodyHtml.querySelector('#content').innerHTML;
		document.body.classList = bodyHtml.classList;
		window.history.pushState('', '', filterUrl);
		document.title = headHtml.querySelector('title').innerHTML
	}

	reloadEvents() {
		new Select2Init();
		$(".woocommerce-ordering").on("change", "select.orderby", function () {
			$(this).closest("form").submit()
		});
		new FlexibleArchiveFilters;
		this.moveEvents();
		const wishlist = new Wishlist;
		wishlist.reload();
	}

	moveEvents() {

		if (!_isDesktop()) {

			if (this.allSidebarFilters) {

				for (const filter of this.allSidebarFilters) {
					//remove and readd widget class for easier styling
					const widget = filter.closest('section');
					widget.classList.remove('widget');
					this.mobileMoveFiltersHook.insertAdjacentElement('beforebegin', widget);
				}

			}

			if (this.allHorizontalFilters) {

				for (const filter of this.allHorizontalFilters) {
					const widget = filter.querySelector('section');
					widget.classList.remove('widget');
				}

			}

			this.elementsMoved = true;

		}

		if (_isDesktop() && this.elementsMoved) {

			if (this.allSidebarFilters.length > 0) {

				for (const filter of this.allSidebarFilters) {
					const widget = filter.closest('section');
					widget.classList.add('widget');
					this.sidebar.insertAdjacentElement('beforeend', widget);
				}

			}

			if (this.allHorizontalFilters.length > 0) {

				for (const filter of this.allHorizontalFilters) {
					const widget = filter.querySelector('section');
					widget.classList.add('widget');
				}

			}

		}

		window.addEventListener('resize', () => {
			setTimeout(() => {
				this.moveEvents();
			}, 300);
		});


		// Close opened filters when clicking outside
		window.addEventListener('click', (e) => {
			let targetFilter = e.target.closest('.archive-filters__col');

			if (this.allHorizontalFilters.length > 0) {
				if (!targetFilter) {
					for (const filter of this.allHorizontalFilters) {
						let filterUl = filter.querySelector('.archive-filters');
						if (filterUl) {
							if (filterUl.classList.contains('expanded')) filterUl.classList.remove('expanded');
						}
					}
				} else {
					for (const filter of this.allHorizontalFilters) {
						if (filter !== targetFilter) {
							let filterUl = filter.querySelector('.archive-filters');
							if (filterUl) {
								if (filterUl.classList.contains('expanded')) filterUl.classList.remove('expanded');
							}
						}
					}
				}
			}

		});

	}

}

export default FlexibleArchiveFilters;
