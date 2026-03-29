<?php
/**
 * This template displays the archive for Products
 *
 * @package genesis_connect_woocommerce
 * @version 0.9.8
 *
 * @since 0.9.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

add_filter( 'woocommerce_show_page_title', '__return_null' );

// Force the Sidebar Content Layout
add_filter( 'ap_wp_site_layout', '__ap_wp_return_sidebar_content' );

/** Remove WooCommerce breadcrumbs */
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

/** Remove Woo #container and #content divs */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

// Remove wc archive description
remove_action( 'woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10 );

get_header();
?>

	<div id="primary" class="content-sidebar-wrap">

		<?php do_action( 'ap_wp_before_content' ); ?>

		<main id="main" class="content mb-svgap md:mb-vgap">
			<?php

			do_action( 'ap_wp_before_entry_content' );
			?>

			<div class="layout-container mx-auto pt-2gap">
				<?php

				/**
				 * For woocommerce_before_main_content hook.
				 *
				 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
				 * @hooked woocommerce_breadcrumb - 20
				 */
				do_action( 'woocommerce_before_main_content' );

				if ( apply_filters( 'woocommerce_show_page_title', true ) ) {
					echo '<h1 class="woocommerce-products-header__title page-title">';

					woocommerce_page_title();

					echo '</h1>';
				}

				do_action( 'woocommerce_archive_description' );

				if ( FLEX_FILTERS_ENABLED ) :

					ob_start();
					?>
					<aside data-currenturl="<?php echo \Classes\Core\FlexibleArchiveFilters::$currentUrl; ?>" id="sidebar-secondary" class="sidebar sidebar-secondary widget-area" role="complementary" aria-label="Archive Filters">
						<div class="archive-filters__toggler"><?php echo __( 'Add or modify filters', 'ap-wp-theme' ) . ap_svg( 'filter-outline', __( 'Open filters', 'ap-wp-theme' ), 'all-filters-open w-[14px] h-[14px] ml-hhgap pointer-events-none', true ); ?></div>
						<div class="archive-filters__row md:flex md:flex-wrap md:gap-gap">
							<div class="archive-filters__row-close"><?php echo ap_svg( 'close-outline', __( 'Open filters', 'ap-wp-theme' ), 'all-filters-close w-[28px] h-[28px] m-hhgap mr-0', true ); ?></div>
							<?php
							echo '<h2 class="screen-reader-text">' . __( 'Archive Filters', 'ap_wp_theme' ) . '</h2>';
							dynamic_sidebar( 'sidebar-2' );
							?>
						</div>
					</aside><!-- #secondary -->
					<?php
					$horizontalSidebar = ob_get_clean();

					if ( ! empty( \Classes\Core\FlexibleArchiveFilters::$removalLinks ) ) :
						echo '<div class="archive-filters__removal-links">';

						foreach ( \Classes\Core\FlexibleArchiveFilters::$removalLinks as $linkArray ) :
							foreach ( $linkArray as $name => $link ) :
								?>
									<a class="archive-filters__removal-link" href="<?php echo $link; ?>"><?php echo $name . ap_svg( 'close-outline', __( 'Remove filter', 'ap-wp-theme' ), 'filters-remove w-[14px] h-[14px] ml-auto transform -rotate-90', true ); ?></a>
									<?php
								endforeach;
							endforeach;

						echo '</div>';
					endif;

					endif;

				if ( have_posts() ) {
					/**
					 * Hook: woocommerce_before_shop_loop.
					 *
					 * @hooked wc_print_notices - 10
					 * @hooked woocommerce_result_count - 20
					 * @hooked woocommerce_catalog_ordering - 30
					 */
					do_action( 'woocommerce_before_shop_loop' );

					?>
					<div class="pt-svgap border-t-black border-t">
					<?php
					woocommerce_product_loop_start();

					if ( wc_get_loop_prop( 'total' ) ) {
						while ( have_posts() ) {
							the_post();

							/**
							 * Hook: woocommerce_shop_loop.
							 *
							 * @hooked WC_Structured_Data::generate_product_data() - 10
							 */
							do_action( 'woocommerce_shop_loop' );

							wc_get_template_part( 'content', 'product' );
						}
					}

					woocommerce_product_loop_end();
					?>
					</div>
					<?php


					/**
					 * Hook: woocommerce_after_shop_loop.
					 *
					 * @hooked woocommerce_pagination - 10
					 */
					do_action( 'woocommerce_after_shop_loop' );
				} else {
					/**
					 * Hook: woocommerce_no_products_found.
					 *
					 * @hooked wc_no_products_found - 10
					 */
					do_action( 'woocommerce_no_products_found' );
				}

					/**
					 * Hook: woocommerce_after_main_content.
					 *
					 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
					 */
					do_action( 'woocommerce_after_main_content' );

					do_action( 'ap_wp_after_entry_content' );

				?>

			</div><!-- layout-container -->

		</main>
		<?php

		do_action( 'ap_wp_after_content' );

		?>
	</div><!-- #primary -->

<?php
get_footer();
