<?php
/**
 *
 * @author Alessio Pangos
 * A custom gallery plugin, that returns a different gallery for simple or variable products, with AJAX integration
 * @version: 1.0
 * @license GPLv3
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

function ap_acf_products_gallery() {

	global $product;

	if ( $product->is_type( 'variable' ) ) {
		ap_acf_products_gallery_variable_product();
	} else {
		ap_acf_products_gallery_simple_product();
	}
}

/**
 * @author Alessio Pangos
 * Gallery for simple products
 */
function ap_acf_products_gallery_simple_product() {
	global $product;
	$attachment_ids = $product->get_gallery_image_ids();

	$hasGallery = false;
	if ( $attachment_ids ) {
		$hasGallery = true;
	}

	if ( has_post_thumbnail( $product->get_id() ) ) {
		array_unshift( $attachment_ids, get_post_thumbnail_id( $product->get_id() ) );
	}

	echo '<div class="relative woo-prod-gal woocommerce-product-gallery woocommerce-product-gallery--with-images images flex flex-row-reverse gap-gap md:col-span-7 max-h-[662px]">';
	if ( get_field( 'mostra_icona_bio', $product->get_id() ) || get_field( 'icona_varieta', $product->get_id() ) ) :
		?>
		<div class="absolute top-hgap right-hgap z-[9] flex flex-col items-start justify-start gap-gap">
			<?php if ( get_field( 'mostra_icona_bio', $product->get_id() ) ) : ?>
				<?php
				ap_svg( 'bio', '', 'w-[90px] h-[60px] fill-black hover:fill-primary transition-all duration-300' );
			endif;
			if ( get_field( 'icona_varieta', $product->get_id() ) ) :
				ap_svg( get_field( 'icona_varieta', $product->get_id() ), '', 'w-[60px] h-[60px] transition-all duration-300 fill-none' );
			endif;
			?>
		</div>
		<?php
	endif;

	?>
	<div class="swiper-container overflow-hidden gallery-top relative md:w-[75%] flex-1">
		<ul class="swiper-wrapper my-gallery" itemscope itemtype="http://schema.org/ImageGallery">
			<?php
			foreach ( $attachment_ids as $attachment_id ) :
				$img     = wp_get_attachment_image_src( $attachment_id, 'woocommerce_single' );
				$imgFull = wp_get_attachment_image_src( $attachment_id, 'full' );
				$imgAlt  = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
				?>
					<li class="swiper-slide bg-light-gray" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
						<a class="woo-prod-gal__link w-full flex items-center h-full" href="<?php echo $imgFull[0]; ?>" itemprop="contentUrl" data-cropped="true" data-pswp-width="<?php echo $imgFull[1]; ?>" data-pswp-height="<?php echo $imgFull[2]; ?>">
							<img loading="lazy" class="woo-prod-gal__image w-auto md:w-full mx-auto h-auto relative my-auto" srcset="<?php echo $img[0]; ?> 1x, <?php echo wr2x_get_retina_from_url( $img[0] ); ?> 2x" src="<?php echo $img[0]; ?>" width="<?php echo $img[1]; ?>" height="<?php echo $img[2]; ?>" alt="<?php echo $imgAlt; ?>">
						</a>
					</li>
				<?php
			endforeach;
			?>
		</ul>

	</div>
	<?php if ( $hasGallery ) : ?>
		<div class="swiper-container overflow-hidden gallery-thumbs mdd:hidden w-[25%] max-h-full h-full">
			<div class="swiper-wrapper max-h-full">
				<?php
				foreach ( $attachment_ids as $attachment_id ) :
					$imgAlt = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
					$img    = wp_get_attachment_image_src( $attachment_id, 'woocommerce_thumbnail' );
					?>
						<div class="swiper-slide bg-light-gray cursor-pointer h-auto">
							<img loading="lazy" alt="<?php echo $imgAlt; ?>" class="woo-prod-gal__image mx-auto h-full w-auto object-contain" srcset="<?php echo $img[0]; ?> 1x, <?php echo wr2x_get_retina_from_url( $img[0] ); ?> 2x" src="<?php echo $img[0]; ?>" width="<?php echo $img[1]; ?>" height="<?php echo $img[2]; ?>">
						</div>
					<?php
				endforeach;
				?>
			</div>
		</div>
		<?php
	endif;

	?>
	<div class="single-product__ajax-loader">
		<img class="single-product__ajax-loader-image" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/ajax-loader.svg" alt="image-loader">
	</div>
	<?php

	echo '</div>';
}

/**
 * @author Alessio Pangos
 * returns the main product gallery
 */
function ap_acf_main_product_gallery_only( $product ) {
	$attachment_ids = $product->get_gallery_attachment_ids();

	$hasGallery = false;

	if ( $attachment_ids ) {
		$hasGallery = true;
	}

	$topGalleryHtml    = '';
	$bottomGalleryHtml = '';

	foreach ( $attachment_ids as $attachment_id ) {

		$imgFull  = wp_get_attachment_image_src( $attachment_id, 'full' );
		$img      = wp_get_attachment_image_src( $attachment_id, 'woocommerce_single' );
		$imgThumb = wp_get_attachment_image_src( $attachment_id, 'woocommerce_thumbnail' );
		$imgAlt   = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );

		ob_start();

		?>
		<li class="swiper-slide bg-light-gray cursor-pointer h-auto" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
			<a class="woo-prod-gal__link w-full flex items-center h-full" href="<?php echo $imgFull[0]; ?>" itemprop="contentUrl" data-cropped="true"  data-cropped="true" data-pswp-width="<?php echo $imgFull[1]; ?>" data-pswp-height="<?php echo $imgFull[2]; ?>">
				<img loading="lazy" class="woo-prod-gal__image w-full h-auto relative my-auto" srcset="<?php echo $img[0]; ?> 1x, <?php echo wr2x_get_retina_from_url( $img[0] ); ?> 2x" src="<?php echo $img[0]; ?>" width="<?php echo $img[1]; ?>" height="<?php echo $img[2]; ?>" alt="<?php echo $imgAlt; ?>">
			</a>
		</li>
		<?php

		$topGalleryHtml .= ob_get_clean();

		ob_start();

		?>
		<div class="swiper-slide bg-light-gray cursor-pointer h-auto">
			<img loading="lazy" alt="<?php echo $imgAlt; ?>" class="woo-prod-gal__image mx-auto h-full w-auto object-contain" srcset="<?php echo $imgThumb[0]; ?> 1x, <?php echo wr2x_get_retina_from_url( $imgThumb[0] ); ?> 2x" src="<?php echo $imgThumb[0]; ?>" width="<?php echo $imgThumb[1]; ?>" height="<?php echo $imgThumb[2]; ?>">
		</div>
		<?php

		$bottomGalleryHtml .= ob_get_clean();

	}

	return array( $topGalleryHtml, $bottomGalleryHtml, $hasGallery );
}

/**
 *
 * @author Alessio Pangos
 * Gallery for variable products
 * @param variation_id_ajax for ajax calls
 */
function ap_acf_products_gallery_variable_product( $variation_id_ajax = null ) {
	global $product;
	// Get variation id from an ajax call or from the default variation
	if ( $variation_id_ajax ) {
		$id           = sanitize_text_field( $_POST['ajax_product_id'] );
		$product      = wc_get_product( $id );
		$variation_id = intval( $variation_id_ajax );
	} else {
		$id = get_the_ID();
		global $product;
		$default_attributes = iconic_get_default_attributes( $product );
		$variation_id       = iconic_find_matching_product_variation( $product, $default_attributes );
	}

	$variations = $product->get_available_variations();

	/**
	 * If a default attribute is set
	 */
	if ( $variation_id ) {
		echo '<div class="woo-prod-gal woocommerce-product-gallery woocommerce-product-gallery--with-images images flex flex-row-reverse gap-gap md:col-span-7 max-h-[662px] relative">';
		if ( get_field( 'mostra_icona_bio', $id ) || get_field( 'icona_varieta', $id ) ) :
			?>
			<div class="absolute top-hgap right-hgap z-[9] flex flex-col items-start justify-start gap-gap">
				<?php if ( get_field( 'mostra_icona_bio', $id ) ) : ?>
					<?php
					ap_svg( 'bio', '', 'w-[90px] h-[60px] fill-black hover:fill-primary transition-all duration-300' );
				endif;
				if ( get_field( 'icona_varieta', $id ) ) :
					ap_svg( get_field( 'icona_varieta', $id ), '', 'w-[60px] h-[60px] transition-all duration-300 fill-none' );
				endif;
				?>
			</div>
			<?php
		endif;
		?>
		<div class="swiper-container overflow-hidden gallery-top relative w-[75%] flex-1">
			<ul class="swiper-wrapper my-gallery" itemscope itemtype="http://schema.org/ImageGallery">
				<?php
				foreach ( $variations as $variation ) :

					if ( $variation['variation_id'] === $variation_id ) :

						$img = $variation['image'];

						ob_start();

						?>
						<li class="swiper-slide bg-light-gray" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
							<a class="woo-prod-gal__link w-full flex items-center h-full" href="<?php echo $variation['image']['src']; ?>" data-pswp-width="<?php echo $variation['image']['full_src_w']; ?>" data-pswp-height="<?php echo $img['full_src_h']; ?>">
								<img loading="lazy" alt="<?php echo $img['alt']; ?>" class="woo-prod-gal__image w-auto md:w-full mx-auto h-auto relative my-auto" width="<?php echo $img['src_w']; ?>" height="<?php echo $img['src_h']; ?>" srcset="<?php echo $img['src']; ?> 1x, <?php echo wr2x_get_retina_from_url( $img['src'] ); ?> 2x" src="<?php echo $img['src']; ?>">
							</a>
						</li>

						<?php
						$variationMainImage = ob_get_clean();

						ob_start();

						?>
						<div class="swiper-slide bg-light-gray">
							<img loading="lazy" alt="<?php echo $img['alt']; ?>" class="woo-prod-gal__image mx-auto h-full w-auto object-contain" srcset="<?php echo $img['gallery_thumbnail_src']; ?> 1x, <?php echo wr2x_get_retina_from_url( $img['gallery_thumbnail_src'] ); ?> 2x" src="<?php echo $img['gallery_thumbnail_src']; ?>" width="<?php echo $img['gallery_thumbnail_src_w']; ?>" height="<?php echo $img['gallery_thumbnail_src_h']; ?>">
						</div>

						<?php

						$variationMainImageBottom = ob_get_clean();

						echo $variationMainImage;

						?>

						<?php
						$hasGallery = false;

						if ( array_key_exists( 'use_main_gallery_only', $_POST ) && $_POST['use_main_gallery_only'] == 'true' ) :
							$mainGallery = ap_acf_main_product_gallery_only( $product );

							if ( $mainGallery[2] ) {
								$hasGallery = true;
							}

							$topGalleryHtml    = $mainGallery[0];
							$bottomGalleryHtml = $mainGallery[1];

						elseif ( have_rows( 'variation_gallery_acf_pro', $id ) ) :

							while ( have_rows( 'variation_gallery_acf_pro', $id ) ) :
								the_row();
								if ( intval( get_sub_field( 'variation_id_acf_pro' ) ) === intval( $variation_id ) ) :
									$hasGallery = true;

									if ( get_sub_field( 'use_main_gallery_for_this_variation' ) ) :

										$mainGallery = ap_acf_main_product_gallery_only( $product );

										$topGalleryHtml    = $mainGallery[0];
										$bottomGalleryHtml = $mainGallery[1];

										else :

											$variationGallery  = get_sub_field( 'gallery' );
											$topGalleryHtml    = '';
											$bottomGalleryHtml = '';

											foreach ( $variationGallery as $currentImage ) :

												ob_start();

												?>
												<li class="swiper-slide bg-light-gray" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
													<a class="woo-prod-gal__link w-full flex items-center h-full" href="<?php echo $currentImage['url']; ?>" itemprop="contentUrl" data-size="<?php echo $currentImage['width']; ?>x<?php echo $currentImage['height']; ?>">
														<img loading="lazy" alt="<?php echo $currentImage['alt']; ?>" width="<?php echo $currentImage['woocommerce_single-width']; ?>" height="<?php echo $currentImage['woocommerce_single-height']; ?>" class="woo-prod-gal__image w-auto md:w-full mx-auto h-auto relative my-auto" srcset="<?php echo $currentImage['sizes']['woocommerce_single']; ?> 1x, <?php echo wr2x_get_retina_from_url( $currentImage['sizes']['woocommerce_single'] ); ?> 2x" src="<?php echo $currentImage['sizes']['woocommerce_single']; ?>">
													</a>
												</li>
												<?php

												$topGalleryHtml .= ob_get_clean();

												ob_start();

												?>
												<div class="swiper-slide bg-light-gray">
													<img loading="lazy" alt="<?php echo $currentImage['alt']; ?>" width="<?php echo $currentImage['woocommerce_thumbnail-width']; ?>" height="<?php echo $currentImage['woocommerce_thumbnail-height']; ?>" class="woo-prod-gal__image w-auto md:w-full mx-auto h-auto" srcset="<?php echo $currentImage['sizes']['woocommerce_thumbnail']; ?> 1x, <?php echo wr2x_get_retina_from_url( $currentImage['sizes']['woocommerce_thumbnail'] ); ?> 2x" src="<?php echo $currentImage['sizes']['woocommerce_thumbnail']; ?>">
												</div>
												<?php

												$bottomGalleryHtml .= ob_get_clean();

											endforeach;

										endif;

									endif;
								endwhile;

						endif;

						if ( $hasGallery ) {
							echo $topGalleryHtml;
						}

					endif;
				endforeach;
				?>
			</ul>

		</div>
		<?php

		if ( $hasGallery ) {
			?>
			<div class="swiper-container overflow-hidden gallery-thumbs mdd:hidden w-[25%] max-h-full h-full">
				<div class="swiper-wrapper max-h-full">
					<?php
					echo $variationMainImageBottom;
					echo $bottomGalleryHtml;
					?>
				</div>
			</div>
			<?php
		}

		?>
		<div class="single-product__ajax-loader">
			<img class="single-product__ajax-loader-image" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/ajax-loader.svg" alt="image-loader">
		</div>
		<?php

		echo '</div>';

	} else {
		ap_acf_products_gallery_simple_product();
	}
}

// Ottiene session id
function ap_get_variation_image_and_gallery_ajax() {
	if ( ! wp_verify_nonce( $_POST['_nonce'], 'ap_ajax_variations' ) ) {
		die( 'Not authorized' );
	}

	ap_acf_products_gallery_variable_product( $_POST['variation_id'] );

	die();
}
add_action( 'wp_ajax_ap_get_variation_image_and_gallery', 'ap_get_variation_image_and_gallery_ajax' );
add_action( 'wp_ajax_nopriv_ap_get_variation_image_and_gallery', 'ap_get_variation_image_and_gallery_ajax' );

/**
 * Get variation default attributes
 *
 * @param WC_Product $product
 * @return array
 */
function iconic_get_default_attributes( $product ) {

	if ( method_exists( $product, 'get_default_attributes' ) ) {

		return $product->get_default_attributes();

	} else {

		return $product->get_variation_default_attributes();

	}
}

/*
 * Find matching product variation
 *
 * @param WC_Product $product
 * @param array $attributes
 * @return int Matching variation ID or 0.
 */
function iconic_find_matching_product_variation( $product, $attributes ) {

	foreach ( $attributes as $key => $value ) {
		if ( strpos( $key, 'attribute_' ) === 0 ) {
			continue;
		}

		unset( $attributes[ $key ] );
		$attributes[ sprintf( 'attribute_%s', $key ) ] = $value;
	}

	if ( class_exists( 'WC_Data_Store' ) ) {

		$data_store = WC_Data_Store::load( 'product' );
		return $data_store->find_matching_product_variation( $product, $attributes );

	} else {

		return $product->get_matching_variation( $attributes );

	}
}
