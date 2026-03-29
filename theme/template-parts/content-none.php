<?php
/**
 * Template part for displaying a message when posts are not found
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ap-wp-theme-tw
 */

?>

<section>

	<?php do_action( 'ap_wp_before_entry_content' ); ?>

	<div class="page-content">
		<?php
		if ( is_home() && current_user_can( 'publish_posts' ) ) :
			?>

			<p>
			<?php esc_html_e( 'Your site is set to show the most recent posts on your homepage, but you haven&rsquo;t published any posts.', 'ap-wp-theme-tw' ); ?></p>

			<p>
				<a href="<?php echo esc_url( admin_url( 'edit.php' ) ); ?>">
					<?php
					/* translators: 1: link to WP admin new post page. */
					esc_html_e( 'Add or publish posts', 'ap-wp-theme-tw' );
					?>
				</a>
			</p>

			<?php
		elseif ( is_search() ) :
			?>

			<p><?php esc_html_e( 'Your search generated no results. Please try a different search.', 'ap-wp-theme-tw' ); ?></p>
			<?php
			get_search_form();

		else :
			?>

			<p><?php esc_html_e( 'No content matched your request.', 'ap-wp-theme-tw' ); ?></p>
			<?php
			get_search_form();

		endif;
		?>
	</div><!-- .page-content -->

	<?php do_action( 'ap_wp_after_entry_content' ); ?>

</section>
