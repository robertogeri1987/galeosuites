<?php

/**
 * Navigation Menu functionality
 *
 * @author Alessio Pangos
 */

namespace Classes\Core;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Nav {


	public $xData;
	public $overlay;
	public $navClasses;
	public $navProperties;
	public $prefix;
	public $beforeMenuArea;
	public $currentPageLink;
	public $currentLink;
	public $isCurrent;
	public $isCurrentClasses;
	public $menu;
	public $navigationMenuUlClasses;
	public $navigationItemClasses;
	public $navigationLinkClasses;
	public $megaMenuClasses;
	public $menuIconClasses;
	public $currentLinkTarget;

	public function __construct( $menu = 'primary' ) {
		global $wp;
		$this->currentPageLink = home_url( $wp->request ) . '/';
		// $this->currentPageLink = add_query_arg( $wp->query_vars, home_url( $wp->request ) ); // Includes query string
		$this->menu = $menu;

		// Primary Menu
		$this->navProperties  = ' aria-label="Main" itemscope="" itemtype="https://schema.org/SiteNavigationElement"';
		$this->prefix         = '';
		$this->beforeMenuArea = '<section class="navigation__mobile-additions"></section>';
		$this->xData          = 'x-data="primaryMenu"';
		$this->overlay        = true;

		$this->navClasses = 'mobile:fixed flex items-center w-screen overflow-visible mobile:flex-col mobile:-top-[100%] mobile:h-[calc(100%-50px)] mobile:mt-[50px] mobile:left-0 mobile:bg-light desk:z-auto desk:layout-container desk:mx-auto desk:left-auto mix-blend-normal desk:h-[88px] desk:items-end desk:pb-gap';

		if ( $this->menu !== 'primary' ) :
			// all other menus
			$this->xData          = 'x-data';
			$this->navProperties  = ' itemtype="https://schema.org/SiteNavigationElement"';
			$this->prefix         = $this->menu . '_';
			$this->beforeMenuArea = '';
			$this->overlay        = false;
			$this->navClasses     = 'flex items-center justify-end desk:w-full ';
		endif;

		$this->currentLink = '';
		$this->isCurrent   = '';

		$this->isCurrentClasses        = ' !font-bold';
		$this->isCurrentSubClasses     = 'text-primary';
		$this->navigationMenuUlClasses = 'w-full mobile:h-screen mobile:overflow-y-auto mobile:pb-dgap desk:flex desk:items-start desk:gap-gap';
		$this->navigationItemClasses   = 'mobile:flex mobile:flex-col ';
		$this->navigationLinkClasses   = 'mobile:py-gap mobile:mx-gap mobile:border-b mobile:border-black flex items-center h-full whitespace-no-wrap relative hover:text-primary hover:font-bold label transition-all duration-300 ease-in-out';
		$this->megaMenuClasses         = 'text-[16px] shadow-md z-[20001] block overflow-hidden mobile:left-full mobile:top-0 mobile:bg-light mobile:h-full mobile:overflow-y-scroll mobile:pb-3gap w-full absolute desk:h-auto desk:block desk:pointer-events-none desk:opacity-0 desk:top-[84px] blend bg-light';
		$this->menuIconClasses         = ' rem:h-[20px] rem:w-[20px] fill-current rem:ml-[5px]';
	}

	public function getIsCurrent( $link ) {
		if ( $link ) {
			$this->currentPageLink === $link['url'] ? $this->isCurrent = $this->isCurrentClasses : $this->isCurrent = '';
		} else {
			$this->isCurrent = '';
		}
	}

	public function specialItem( $itemType ) {
		if ( $itemType === 'Account' ) {
			new \Components\Nav\AccountWidget( $this );
		}
		if ( $itemType === 'Ricerca' ) {
			new \Components\Nav\SearchWidget( $this );
		}
		if ( $itemType === 'Carrello' ) {
			if ( class_exists( 'WooCommerce' ) ) {
				new \Components\Nav\NavigationItemCart( $this );
			}
		}
		if ( $itemType === 'Wishlist' ) {
			new \Components\Nav\WishlistWidget( $this );
		}
		if ( $itemType === 'Lingua' ) { ?>
			<li class="mobile:flex mobile:flex-col" x-data="menuItem" x-ref="menuItem">
				<div class="mobile:flex mobile:pl-hgap mobile:pt-hhgap">
					<select data-lang-select onchange="location = this.value;">
						<option value="#" selected disabled><?php _e( 'Scegli la lingua', 'ap-wp-theme-tw' ); ?></option>
						<?php
						$languages = icl_get_languages( 'skip_missing=1&orderby=code' );
						foreach ( $languages as $language ) {
							echo '<option value="' . $language['url'] . '"';
							if ( $language['active'] ) {
								echo ' selected';
							}
							echo '>' . $language['translated_name'] . '</option>';
						}
						?>
					</select>
				</div>
			</li>
			<?php
		}
	}

	public function render() {
		echo $this->beforeMenuArea;
		?>
		<nav
			id="<?php echo 'navigation-' . $this->menu; ?>"
			class="<?php echo $this->navClasses; ?>"
			<?php echo $this->xData; ?>
			<?php echo $this->navProperties; ?>
			:class="!$store.header.menuOpen && $store.header.isWhite && !$store.header.hasScrolled && !$store.header.isScrolling ? 'text-white' : 'text-black' ">
			<?php new \Components\Nav\NavigationMenuUl( $this ); ?>
			<?php if ( $this->overlay ) : ?>
				<?php new \Components\Nav\Overlay(); ?>
			<?php endif; ?>
		</nav>
		<?php
	}
}
