<?php
/**
 * Template part for displaying wishlist content in the wishlist template
 *
 * @author Alessio Pangos
 *
 * @package ap-wp-theme
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$currentWishList = \Classes\Core\Wishlist::GetCurrentWishlist();

if ( count( $currentWishList ) === 0 ) {
	echo '<p class="text-center">' . esc_html__( 'La tua lista desideri è vuota', 'ap-wp-theme' ) . '</p>';
	return;
}
$getItems = new WP_Query(
	array(
		'post_type'      => 'product',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'post__in'       => $currentWishList,
	)
);

echo '<ul class="products columns-3">';

if ( $getItems->have_posts() ) :
	while ( $getItems->have_posts() ) :
		$getItems->the_post();

		wc_get_template_part( 'content', 'product' );

	endwhile;
endif;

echo '</ul>';

wp_reset_postdata();
