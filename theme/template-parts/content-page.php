<?php
/**
 * Template part for displaying pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ap-wp-theme-tw
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php do_action( 'ap_wp_before_entry_content' ); ?>

	<div class="entry-content inner-container mx-auto">
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
