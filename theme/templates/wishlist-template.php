<?php
/**
 * Template Name: Wishlist Page
 *
 * @package      ap-wp-theme
 * @author       Alessio Pangos
 * @license      GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
add_filter(
	'woocommerce_show_page_title',
	function () {
		return null;
	}
);

/** Remove WooCommerce breadcrumbs */
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

/** Remove Woo #container and #content divs */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

// Remove wc archive description
remove_action( 'woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10 );

remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

get_header();
?>

<section id="primary">

	<?php do_action( 'ap_wp_before_content' ); ?>

	<main id="main" class="content max-w-full overflow-x-clip">
		<div class="layout-container mx-auto relative z-[2]">
			<?php

			/* Start the Loop */
			while ( have_posts() ) :
				the_post();

				get_template_part( 'template-parts/content', 'wishlist' );

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}

			endwhile; // End of the loop.

			?>
		</div>
	</main><!-- #main -->

	<?php

	do_action( 'ap_wp_after_content' );

	?>

</section><!-- #primary -->

<?php
get_footer();
