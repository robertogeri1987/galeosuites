<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ap-wp-theme-tw
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

get_header();

remove_action( 'ap_wp_before_entry_content', 'ap_wp_page_header' );

?>

	<section id="primary">

		<?php do_action( 'ap_wp_before_content' ); ?>

		<main id="main">

			<?php

			/* Start the Loop */
			while ( have_posts() ) :
				the_post();

				get_template_part( 'template-parts/content', 'page' );

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
