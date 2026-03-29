<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package ap-wp-theme-tw
 */

 // If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

get_header();
?>

	<section id="primary">

		<?php do_action( 'ap_wp_before_content' ); ?>

		<main id="main">

			<?php do_action( 'ap_wp_before_entry_content' ); ?>

			<div class="inner-container mx-auto mt-hgap grid gap-hgap">

				<?php if ( have_posts() ) : ?>

					<?php
					// Start the Loop.
					while ( have_posts() ) :
						the_post();
						get_template_part( 'template-parts/archive', 'search' );

						// End the loop.
					endwhile;

					// Previous/next page navigation.
				else :

					// If no content, include the "No posts found" template.
					get_template_part( 'template-parts/content', 'none' );

				endif;
				?>

			</div>

			<?php do_action( 'ap_wp_after_entry_content' ); ?>

			<?php ap_wp_numeric_posts_nav(); ?>

		</main><!-- #main -->

		<?php

		do_action( 'ap_wp_after_content' );

		?>

	</section><!-- #primary -->

<?php
get_footer();
