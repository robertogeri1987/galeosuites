<?php
/**
 * Template Name: Dummy Template
 * A dummy template just for assigning block custom fields used in the page builder
 *
 * @package      ap-wp-theme
 * @author       Alessio Pangos
 * @license      GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

get_header();
?>

	<div id="primary" class="content-area">

		<?php do_action( 'ap_wp_before_content' ); ?>

		<main id="main" class="site-main">

		<?php

		do_action( 'ap_wp_before_entry_content' );

		if ( have_posts() ) :

			/* Start the Loop */
			while ( have_posts() ) :
				the_post();

				/*
				 * Include the Post-Type-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
				 */
				get_template_part( 'template-parts/content', get_post_type() );

			endwhile;

			the_posts_navigation();

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif;

		do_action( 'ap_wp_after_entry_content' );

		?>

		</main><!-- #main -->

		<?php

		do_action( 'ap_wp_after_content' );

		get_sidebar();

		?>

	</div><!-- #primary -->

<?php
get_footer();
