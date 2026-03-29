<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package ap-wp-theme-tw
 */

 // If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

get_header();
remove_action('ap_wp_before_entry_content', 'ap_wp_page_header');

?>

	<section id="primary">

		<?php do_action( 'ap_wp_before_content' ); ?>

		<main id="main">

			<?php
			while ( have_posts() ) :
				the_post();

				get_template_part( 'template-parts/content', get_post_type() );

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile; // End of the loop.
			?>

		</main><!-- #main -->

		<?php

		do_action( 'ap_wp_after_content' );

		?>

	</section><!-- #primary -->

<?php
get_footer();
