<?php

/**
 * This template displays the single Product
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

add_action( 'wp_enqueue_scripts', 'ap_woocommerce_single_product_scripts_styles', 21 );
function ap_woocommerce_single_product_scripts_styles() {

	$ajax_url = admin_url( 'admin-ajax.php' );
	get_field( 'use_main_gallery_as_variations_gallery' ) ? $useMain = 'true' : $useMain = 'false';

	wp_localize_script(
		'ap-wp-theme-tw-script',
		'ajaxParamsSingleProd',
		array(
			'url'                  => $ajax_url,
			'nonce'                => wp_create_nonce( 'ap_ajax_variations' ),
			'ajax_product_id'      => get_the_ID(),
			'main_gallery_for_all' => 'true',
		)
	);
}

/** Remove WooCommerce breadcrumbs */
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

/** Remove Woo #container and #content divs */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
add_action( 'woocommerce_before_single_product_summary', 'ap_acf_products_gallery', 20 );

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );

// Remove add to cart quantity field
function ap_wp_remove_all_quantity_fields( $return, $product ) {
	return true;
}
add_filter( 'woocommerce_is_sold_individually', 'ap_wp_remove_all_quantity_fields', 10, 2 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );

// Add like button next to add to cart
if ( WISHLIST_FUNCTIONALITY_ENABLED ) {
	add_action( 'woocommerce_after_add_to_cart_button', 'ap_wp_wishlist_button' );
	function ap_wp_wishlist_button() {
		\Classes\Core\Wishlist::LikeHtml();
	}
}

remove_action( 'ap_wp_before_entry_content', 'ap_wp_page_header' );

add_action( 'woocommerce_after_single_product', 'ap_wp_gramigna_prod_content' );
function ap_wp_gramigna_prod_content() {
	the_content();
}

add_filter( 'woocommerce_product_upsells_products_heading', 'ap_wp_gramigna_upsell_heading' );
function ap_wp_gramigna_upsell_heading() {
	return '' . __( 'Potrebbe piacerti anche:', 'ap-wp-theme' );
}

add_action( 'woocommerce_after_single_product', 'gramigna_altre_annate', 10 );
function gramigna_altre_annate() {
	$annate = get_field( 'altre_annate' );
	if ( $annate ) :
		$relProds = new \WP_Query(
			array(
				'posts_per_page' => -1,
				'post_type'      => 'product',
				'orderby'        => 'post__in',
				'post__in'       => $annate,
			)
		);
		if ( $relProds->have_posts() ) :

			?>
			<h2 class="title-lg mb-gap mt-svgap md:mt-vgap">
				<?php _e( 'Altre annate:', 'ap-wp-theme' ); ?>
			</h2>
			<ul class="grid grid-cols-2 md:grid-cols-4 gap-gap products">
				<?php
				while ( $relProds->have_posts() ) :
					$relProds->the_post();
					wc_get_template_part( 'content', 'product' );
				endwhile;
				?>
			</ul>
			<?php
		endif;

		wp_reset_postdata();
		?>
		<?php
	endif;
}

remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
add_action( 'woocommerce_after_single_product', 'woocommerce_upsell_display', 15 );

get_header();
?>

<div id="primary" class="content-sidebar-wrap">

	<?php do_action( 'ap_wp_before_content' ); ?>

	<main id="main" class="content mb-svgap md:mb-vgap">

		<div class="layout-container mx-auto mt-svgap md:mt-vgap pt-2gap">

			<?php

			do_action( 'ap_wp_before_entry_content' );

			do_action( 'woocommerce_before_main_content' );

			// Let developers override the query used, in case they want to use this function for their own loop/wp_query
			$wc_query = false;

			// Added a hook for developers in case they need to modify the query
			$wc_query = apply_filters( 'ap_wp_custom_query', $wc_query );

			if ( ! $wc_query ) {

				global $wp_query;

				$wc_query = $wp_query;
			}

			if ( $wc_query->have_posts() ) {
				while ( $wc_query->have_posts() ) :
					$wc_query->the_post();
					?>

					<?php do_action( 'woocommerce_before_single_product' ); ?>

					<div id="product-<?php the_ID(); ?>" <?php post_class(); ?>>

						<div class="md:grid md:grid-cols-12 gap-gap">

							<?php do_action( 'woocommerce_before_single_product_summary' ); ?>

							<div class="summary md:col-span-5">

								<div class="summary__sticky md:flex md:flex-col h-full">
									<?php woocommerce_breadcrumb(); ?>

									<h1 class="title-lg uppercase font-medium mdd:mt-hgap"><?php echo get_the_title(); ?></h1>

									<?php
									$longDesc = get_field( 'descrizione_lunga_prodotto' );
									if ( $longDesc ) {
										?>
										<div class="text-sm mt-gap w-[464px] max-w-full">
											<?php echo $longDesc; ?>
										</div>
										<?php
									}

									// Add product attributes to list.
									$attributes = array_filter( $product->get_attributes(), 'wc_attributes_array_filter_visible' );

									if ( $attributes ) :
										?>
										<section class="mt-gap w-[464px] max-w-full">
											<ul class="grid grid-cols-1 gap-hhgap">
												<?php
												foreach ( $attributes as $attribute ) :
													$values = array();
													if ( $attribute->is_taxonomy() ) {
														$attribute_taxonomy = $attribute->get_taxonomy_object();
														$attribute_values   = wc_get_product_terms( $product->get_id(), $attribute->get_name(), array( 'fields' => 'all' ) );

														foreach ( $attribute_values as $attribute_value ) {
															$value_name = esc_html( $attribute_value->name );
															$values[]   = $value_name;
														}
													}

													?>
													<li class="grid grid-cols-3 gap-hgap text-sm first:mt-0 mt-hhgap">
														<span class="font-medium uppercase"><?php echo wc_attribute_label( $attribute->get_name() ); ?></span>
														<span class="text-left col-span-2"><?php echo implode( ', ', $values ); ?></span>
													</li>
													<?php
												endforeach;
												?>
											</ul>
											<?php echo get_the_content(); ?>
										</section>
										<?php
									endif;
									?>

									<?php do_action( 'woocommerce_single_product_summary' ); ?>
									<?php
									$sku = $product->get_sku();
									$pdf = get_field( 'pdf_scheda_tecnica' );
									if ( $sku || $pdf ) :
										?>
										<div class="flex gap-gap items-center justify-start">
											<?php
											if ( $sku ) :
												?>
												<div class="mt-hgap text-sm">
													<span class="uppercase"><?php esc_html_e( 'SKU', 'ap-wp-theme' ); ?></span>
													<span class="text-left ml-2"><?php echo esc_html( $sku ); ?></span>
												</div>
												<?php
											endif;
											if ( $pdf ) :
												?>
												<div class="mt-hgap text-sm<?php echo $sku ? ' ml-auto' : ''; ?>">
													<a class="underline" href="<?php echo $pdf; ?>" download target="_blank">
														<?php echo __( 'Scarica pdf scheda tecnica', 'ap-wp-theme' ); ?>
													</a>
												</div>
												<?php
											endif;
											?>
										</div>
										<?php
									endif;
									?>
								</div>

							</div>

						</div>

						<?php do_action( 'woocommerce_after_single_product_summary' ); ?>

					</div>

					<?php
					do_action( 'woocommerce_after_single_product' );

				endwhile;
			}
			do_action( 'woocommerce_after_main_content' );

			do_action( 'ap_wp_after_entry_content' );

			?>

		</div><!-- layout-container -->
	</main>
	<?php

	do_action( 'ap_wp_after_content' );

	?>
</div><!-- #primary -->

<?php
get_footer();
