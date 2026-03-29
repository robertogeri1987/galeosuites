<?php
/**
 * The template for displaying Blog Page
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

add_action( 'ap_wp_before_entry_content', 'ap_wp_theme_archive_cat_filters', 99 )
?>

	<section id="primary">

		<?php do_action( 'ap_wp_before_content' ); ?>

		<main id="main" class="pb-svgap md:pb-vgap">

			<?php

			do_action( 'ap_wp_before_entry_content' );

			if ( have_posts() ) :

				?>
				<div class="layout-container mx-auto grid mdd:gap-y-2gap gap-gap grid-cols-1 md:grid-cols-12 pt-svgap">
				<?php

				$featPosts = new \WP_Query(
					array(
						'post_type'   => 'post',
						'post_status' => 'publish',
						'showposts'   => 3,
						'orderby'     => 'date',
						'order'       => 'desc',
						'meta_query'  => array(
							array(
								'key'     => 'featured_post',
								'value'   => 1,
								'compare' => '=',
							),
						),
					)
				);

				if ( $featPosts->have_posts() ) :
					$counter = 1;
					?>
						<?php
						while ( $featPosts->have_posts() ) :
							$featPosts->the_post();

							if ( $counter === 1 ) :

								get_template_part( 'template-parts/featured', get_post_type() );

							endif;
							++$counter;
						endwhile;
						?>
						<?php
				endif;

				wp_reset_postdata();

					/* Start the Loop */
				while ( have_posts() ) :
					the_post();

					get_template_part( 'template-parts/archive', get_post_type() );

				endwhile;
				?>
				</div>
				<?php ap_wp_numeric_posts_nav(); ?>
				<?php
				// with post object by id
				$post        = get_post( get_option( 'page_for_posts' ) );
				$the_content = apply_filters( 'the_content', $post->post_content );
				if ( ! empty( $the_content ) ) :
					echo $the_content;
				endif;

			else :

				// with post object by id
				$post        = get_post( get_option( 'page_for_posts' ) );
				$the_content = apply_filters( 'the_content', $post->post_content );
				if ( ! empty( $the_content ) ) {
					echo $the_content;
				}

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
