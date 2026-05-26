<?php
/**
 * Template part for displaying the header content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ap-wp-theme-tw
 */

?>

<header x-data class="fixed top-0 left-0 w-full max-w-full mdd:h-[50px] z-[999]" data-main-header :class="$store.header.menuOpen || $store.header.hasScrolled ? ' bg-light shadow-sm' : ' bg-transparent'" x-init="$store.header.isWhite = <?php echo get_field( 'testo_bianco_header' ) ? 'true' : 'false'; ?>">
	<div class="flex items-center justify-between flex-wrap md:flex-no-wrap md:relative h-full">
		<div class="absCenter block py-hgap max-w-[70px] md:max-w-[143px] z-[4]">
			<?php
			if ( is_front_page() && is_home() ) :
				?>
				<span class="sr-only"><?php bloginfo( 'name' ); ?></span>
				<h1 class="text-3xl font-bold leading-none whitespace-no-wrap my-0 !mt-0" x-data x-cloak>
					<a class="text-white" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
						<img x-show="!$store.header.isWhite || $store.header.isScrolling || $store.header.hasScrolled || $store.header.menuOpen" width="113" height="63" class="mdd:w-[100px] mdd:h-auto" alt="Gramigna Logo Black" src="<?php echo get_stylesheet_directory_uri() . '/assets/images/logo.svg'; ?>">
						<img x-show="!$store.header.menuOpen && $store.header.isWhite && !$store.header.isScrolling && !$store.header.hasScrolled" width="113" height="63" class="mdd:w-[100px] mdd:h-auto" alt="Gramigna Logo Black" src="<?php echo get_stylesheet_directory_uri() . '/assets/images/logo-dark.svg'; ?>">
					</a>
				</h1>
				<?php
			else :
				?>
				<span class="sr-only"><?php bloginfo( 'name' ); ?></span>
				<p class="text-3xl font-bold leading-none whitespace-no-wrap my-0 !mt-0" x-data x-cloak>
					<a class="text-white" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
						<img x-show="!$store.header.isWhite || $store.header.isScrolling || $store.header.hasScrolled || $store.header.menuOpen" width="113" height="63" class="mdd:w-[100px] mdd:h-auto" alt="Gramigna Logo Black" src="<?php echo get_stylesheet_directory_uri() . '/assets/images/logo.svg'; ?>">
						<img x-show="!$store.header.menuOpen && $store.header.isWhite && !$store.header.isScrolling && !$store.header.hasScrolled" width="113" height="63" class="mdd:w-[100px] mdd:h-auto" alt="Gramigna Logo Black" src="<?php echo get_stylesheet_directory_uri() . '/assets/images/logo-dark.svg'; ?>">
					</a>
				</p>
				<?php
			endif;
			?>
		</div><!-- .site-branding -->
		<?php
		$primaryMenu = new \Classes\Core\Nav();
		$primaryMenu->render();
		new \Components\Nav\Toggler();
		?>
	</div>
</header><!-- data-main-header -->
