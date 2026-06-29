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

	<section class="footer-map" aria-label="<?php esc_attr_e( 'Mappa', 'ap-wp-theme-tw' ); ?>">
		<iframe
			class="block w-full h-[300px] md:h-[450px] border-0"
			src="https://maps.google.com/maps?q=<?php echo rawurlencode( 'Galeo Srl, Piazza Giacomo Puccini 5, 50144 Firenze FI' ); ?>&hl=it&z=16&output=embed"
			title="Galeo Srl - Piazza Giacomo Puccini 5, 50144 Firenze, FI"
			loading="lazy"
			referrerpolicy="no-referrer-when-downgrade"
			allowfullscreen></iframe>
	</section>

	<?php get_template_part( 'template-parts/layout/footer', 'content' ); ?>

	<?php do_action( 'ap_wp_after_footer' ); ?>

</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
