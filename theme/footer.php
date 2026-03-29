<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package ap-wp-theme-tw
 */

?>

	</div><!-- #content -->

	<?php do_action( 'ap_wp_before_footer' ); ?>

	<?php get_template_part( 'template-parts/layout/footer', 'content' ); ?>

	<?php do_action( 'ap_wp_after_footer' ); ?>

</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
