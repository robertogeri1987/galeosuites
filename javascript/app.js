import polyfills from './polyfills/index.js';
import AlpineStoreHeader from './modules/AlpineStoreHeader.js';
import AlpineMenu from './modules/AlpineMenu.js';
import AlpineMenuItem from './modules/AlpineMenuItem.js';
import AlpineTimeline from './modules/AlpineTimeline.js';
import AlpineWCVariationsSelect from './modules/AlpineWCVariationsSelect.js';
import AlpineWooCart from './modules/AlpineWooCart.js';
import AlpineWooCheckout from './modules/AlpineWooCheckout.js';
import AlpineWooNotices from './modules/AlpineWooNotices.js';
import APWPPopup from './modules/APWPPopup.js';
import DatePicker from './modules/DatePicker.js';
import ExpandableBlock from './modules/ExpandableBlock.js';
import FlexibleArchiveFilters from './modules/FlexibleArchiveFilters.js';
import OnScroll from './modules/effects/OnScroll.js';
import VideoPlyr from './modules/VideoPlyr.js';
import PredictiveSearch from './modules/PredictiveSearch.js';
import ProductGalleries from './modules/ProductGalleries.js';
import ScrollEvents from './modules/ScrollEvents.js';
import Select2Init from './modules/Select2Init.js';
// import SetCSSGlobalVars from './modules/SetCSSGlobalVars.js';
import SlidersInit from './modules/SlidersInit.js';
import Tabs from './modules/Tabs.js';
import Wishlist from './modules/Wishlist.js'

export const initializeScripts = () => {
	// new SetCSSGlobalVars();

	/**
	 * Init Alpine js
	 */
	import('alpinejs').then((
		{ default: Alpine }
	) => {
		import('alpinejs-breakpoints').then((
			{ default: breakpoint }
		) => {
			import('@alpinejs/anchor').then((
				{ default: anchor }
			) => {
				import('@alpinejs/resize').then((
					{ default: resize }
				) => {

					// Lazyload still useful for videos
					import('vanilla-lazyload').then((
						{ default: LazyLoad }
					) => {
						new LazyLoad({
							elements_selector: ".lazyload"
						});
					});

					new APWPPopup();
					new DatePicker();
					new ExpandableBlock();
					new FlexibleArchiveFilters();
					new PredictiveSearch();
					new ProductGalleries();
					new OnScroll();
					new ScrollEvents();
					new Select2Init();
					new SlidersInit();
					new Tabs();
					new VideoPlyr();
					// new Wishlist();

					Alpine.plugin(breakpoint);
					window.AlpineBreakpointPluginBreakpointsList = ['unset', 'xxs', 'xs', 'sm', 'md', 'lg', 'xl', '2xl'];

					Alpine.plugin(anchor);
					Alpine.plugin(resize);

					window.Alpine = Alpine;
					new AlpineStoreHeader();
					new AlpineMenu();
					new AlpineMenuItem();
					new AlpineTimeline();
					new AlpineWCVariationsSelect();
					new AlpineWooCart();
					new AlpineWooCheckout();
					new AlpineWooNotices();

					Alpine.start();
				});
			});
		});
	});

}

const polyfilledBlockEditorScripts = () => {
	Promise.all(polyfills)
		.then(initializeScripts())
		.catch((error) => {
			console.error('Failed fetching polyfills', error)
		}
		);
}

Promise.all(polyfills)
	.then(initializeScripts())
	.catch((error) => {
		console.error('Failed fetching polyfills', error)
	}
	);

// Initialize dynamic block preview (editor).
if (window.acf) {
	window.acf.addAction('render_block_preview', polyfilledBlockEditorScripts);
}
