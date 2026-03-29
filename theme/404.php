<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package ap-wp-theme-tw
 */

get_header();
?>

	<section id="primary">
		<main id="main">

			<div class="inner-container mx-auto mt-gap">
				<header class="page-header">
					<h1 class="title-md"><?php esc_html_e( 'Page Not Found', 'ap-wp-theme-tw' ); ?></h1>
				</header><!-- .page-header -->

				<div class="page-content prose">
					<p><?php esc_html_e( 'This page could not be found. It might have been removed or renamed, or it may never have existed.', 'ap-wp-theme-tw' ); ?></p>
					<?php get_search_form(); ?>
				</div><!-- .page-content -->
			</div>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php
get_footer();
