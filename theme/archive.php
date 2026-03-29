<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ap-wp-theme-tw
 */

get_header();

add_action( 'ap_wp_before_entry_content', 'ap_wp_theme_archive_cat_filters', 99 )
?>

	<section id="primary">

		<?php do_action( 'ap_wp_before_content' ); ?>

		<main id="main" class="pb-svgap md:pb-vgap">

			<?php

			do_action( 'ap_wp_before_entry_content' );

			if ( have_posts() ) :

				?>
				<div class="layout-container mx-auto grid gap-gap grid-cols-1 md:grid-cols-12 pt-svgap">
				<?php

					/* Start the Loop */
				while ( have_posts() ) :
					the_post();

					get_template_part( 'template-parts/archive', get_post_type() );

				endwhile;
				?>
				</div>
				<?php ap_wp_numeric_posts_nav(); ?>
				<?php


			else :

				// If no content, include the "No posts found" template.
				get_template_part( 'template-parts/content', 'none' );

			endif;

			do_action( 'ap_wp_after_entry_content' );

			?>

		</main><!-- #main -->

		<?php

		do_action( 'ap_wp_after_content' );

		?>

	</section><!-- #primary -->

<?php
get_footer();
