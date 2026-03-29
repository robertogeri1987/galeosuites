<?php
/**
 * Template Name: Checkout Page
 *
 * @package      ap-wp-theme
 * @author       Alessio Pangos
 * @license      GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

remove_action( 'ap_wp_before_entry_content', 'ap_wp_page_header' );
add_action( 'woocommerce_checkout_before_customer_details', 'ap_wp_page_header' );
remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );
add_action( 'woocommerce_checkout_after_customer_details', 'woocommerce_checkout_payment', 20 );

// remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form' );
// add_action( 'woocommerce_checkout_before_customer_details', 'woocommerce_checkout_coupon_form' );

get_header();
?>

<section id="primary">

	<?php do_action( 'ap_wp_before_content' ); ?>

	<main id="main" x-data="woocheckout" class="pt-2gap">

		<?php

		/* Start the Loop */
		while ( have_posts() ) :
			the_post();

			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			<?php do_action( 'ap_wp_before_entry_content' ); ?>

				<div class="entry-content inner-container relative mx-auto min-h-screen pb-svgap">
					<?php
					the_content();

					wp_link_pages(
						array(
							'before' => '<div>' . __( 'Pages:', 'ap-wp-theme-tw' ),
							'after'  => '</div>',
						)
					);
					?>
				</div><!-- .entry-content -->

			<?php do_action( 'ap_wp_after_entry_content' ); ?>

			</article><!-- #post-<?php the_ID(); ?> -->
			<?php

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}

		endwhile; // End of the loop.

		?>

	</main><!-- #main -->

	<?php

	do_action( 'ap_wp_after_content' );

	?>

</section><!-- #primary -->

<?php
get_footer();
