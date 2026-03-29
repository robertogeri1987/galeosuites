<?php
/**
 *
 *  Woocommerce scripts and functions
 *
 *  @author Alessio Pangos
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
// Dequeue all woocommerce styles
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

function ap_wp_disable_wp_blocks() {
	$wstyles = array(
		'wp-block-library',
		'wc-blocks-style',
		'wc-blocks-style-active-filters',
		'wc-blocks-style-add-to-cart-form',
		'wc-blocks-packages-style',
		'wc-blocks-style-all-products',
		'wc-blocks-style-all-reviews',
		'wc-blocks-style-attribute-filter',
		'wc-blocks-style-breadcrumbs',
		'wc-blocks-style-catalog-sorting',
		'wc-blocks-style-customer-account',
		'wc-blocks-style-featured-category',
		'wc-blocks-style-featured-product',
		'wc-blocks-style-mini-cart',
		'wc-blocks-style-price-filter',
		'wc-blocks-style-product-add-to-cart',
		'wc-blocks-style-product-button',
		'wc-blocks-style-product-categories',
		'wc-blocks-style-product-image',
		'wc-blocks-style-product-image-gallery',
		'wc-blocks-style-product-query',
		'wc-blocks-style-product-results-count',
		'wc-blocks-style-product-reviews',
		'wc-blocks-style-product-sale-badge',
		'wc-blocks-style-product-search',
		'wc-blocks-style-product-sku',
		'wc-blocks-style-product-stock-indicator',
		'wc-blocks-style-product-summary',
		'wc-blocks-style-product-title',
		'wc-blocks-style-rating-filter',
		'wc-blocks-style-reviews-by-category',
		'wc-blocks-style-reviews-by-product',
		'wc-blocks-style-product-details',
		'wc-blocks-style-single-product',
		'wc-blocks-style-stock-filter',
		'wc-blocks-style-cart',
		'wc-blocks-style-checkout',
		'wc-blocks-style-mini-cart-contents',
		'classic-theme-styles-inline',
	);

	foreach ( $wstyles as $wstyle ) {
		wp_deregister_style( $wstyle );
	}

	$wscripts = array(
		'wc-blocks-middleware',
		'wc-blocks-data-store',
	);

	foreach ( $wscripts as $wscript ) {
		wp_deregister_script( $wscript );
	}
}

add_action( 'wp_enqueue_scripts', 'ap_woocommerce_scripts_styles', 21 );
function ap_woocommerce_scripts_styles() {

	wp_dequeue_script( 'select2' );
	wp_dequeue_script( 'photoswipe' );
	wp_dequeue_script( 'photoswipe-ui-default' );
	wp_dequeue_script( 'prettyPhoto' );
	wp_dequeue_script( 'prettyPhoto-init' );
	wp_dequeue_script( 'zoom' );
	wp_dequeue_script( 'flexslider' );
	// wp_dequeue_style( 'flexslider' );
	// wp_dequeue_style( 'woocommerce_frontend_styles' );
	// wp_dequeue_style( 'woocommerce-layout' );
	wp_dequeue_style( 'select2' );
	wp_dequeue_style( 'selectWoo' );
	$ajax_url = admin_url( 'admin-ajax.php' );

	if ( class_exists( 'WooCommerce' ) && is_checkout() ) {
		// Use Select2 instead of choices only for checkout
		wp_enqueue_script( 'select2' );
		wp_enqueue_script( 'selectWoo' );
		wp_enqueue_script( 'select2-init', get_bloginfo( 'stylesheet_directory' ) . '/assets/scripts/legacy/min/select2-init.min.js', array( 'selectWoo' ), GLOBAL_THEME_VERSION );
		// Localize Our Script so we can use `ajax_url`
		wp_localize_script(
			'ap-wp-theme-tw-script',
			'ajaxParams',
			array(
				'assetsDir'                 => get_stylesheet_directory_uri() . '/assets/',
				'url'                       => $ajax_url,
				'isCheckout'                => true,
				'freeShippingPriceTxt'      => __( 'Gratuita', 'ap-wp-theme' ),
				'genericCheckoutFieldError' => __( 'Campo obbligatorio ', 'ap-wp-theme' ),
				'noShippingAvailable'       => __( 'Nessuna opzione di spedizione disponibile.', 'ap-wp-theme' ),
			)
		);
	}
	wp_localize_script(
		'ap-wp-theme-tw-script',
		'cartParams',
		array(
			'url'           => $ajax_url,
			'nonce'         => wp_create_nonce( 'cart_nonce' ),
			'cart_hash_key' => WC()->ajax_url() . '-wc_cart_hash',
			'noProducts'    => __( 'Your cart is currently empty.', 'woocommerce' ),
		)
	);
}

// Load theme helper functions.
require_once get_stylesheet_directory() . '/woocommerce/inc/acf-products-gallery.php';
require_once get_stylesheet_directory() . '/woocommerce/inc/ap-wp-product-variations.php';
require_once get_stylesheet_directory() . '/woocommerce/inc/cart-customizations.php';
require_once get_stylesheet_directory() . '/woocommerce/inc/checkout-customizations.php';
// require_once get_stylesheet_directory() . '/woocommerce/inc/ap-wp-admin-prod-galleries.php';

/* Gallery Thumbnails Sizes */
add_filter(
	'woocommerce_get_image_size_gallery_thumbnail',
	function ( $size ) {
		return array(
			'width'  => 206,
			'height' => 206,
			'crop'   => 1,
		);
	}
);

/*
Start Disable Continue Shopping Message after Add to Cart
*/

add_filter(
	'wc_add_to_cart_message_html',
	function ( $string, $product_id = 0 ) {
		$start = strpos( $string, '<a ' ) ?: 0;
		$end   = strpos( $string, '</a>', $start ) ?: 0;
		return substr( $string, 0, $start ) . substr( $string, $end );
	}
);

/**
 *  Enable Gutenberg for wc products
 */
// Disable new WooCommerce product template (from Version 7.7.0)
function ap_wp_theme_greis_reset_product_template( $post_type_args ) {
	if ( array_key_exists( 'template', $post_type_args ) ) {
		unset( $post_type_args['template'] );
	}
	return $post_type_args;
}
add_filter( 'woocommerce_register_post_type_product', 'ap_wp_theme_greis_reset_product_template' );

// Enable Gutenberg editor for WooCommerce
function ap_wp_theme_greis_activate_gutenberg_product( $can_edit, $post_type ) {
	if ( $post_type == 'product' ) {
		$can_edit = true;
	}
	return $can_edit;
}
add_filter( 'use_block_editor_for_post_type', 'ap_wp_theme_greis_activate_gutenberg_product', 10, 2 );

// Enable taxonomy fields for woocommerce with gutenberg on
function ap_wp_theme_greis_enable_taxonomy_rest( $args ) {
	$args['show_in_rest'] = true;
	return $args;
}
add_filter( 'woocommerce_taxonomy_args_product_cat', 'ap_wp_theme_greis_enable_taxonomy_rest' );
add_filter( 'woocommerce_taxonomy_args_product_tag', 'ap_wp_theme_greis_enable_taxonomy_rest' );

/*
End Disable Continue Shopping Message after Add to Cart
*/

// Add Cart refresh to wc fragments
add_filter( 'woocommerce_add_to_cart_fragments', 'ap_wp_woocommerce_header_add_to_cart_fragment' );
function ap_wp_woocommerce_header_add_to_cart_fragment( $fragments ) {

	ob_start();

	\Classes\Core\WCCartWidget::ReturnWcCart();

	$fragments['[data-nav-cart]'] = ob_get_clean();
	return $fragments;
}

// Wishlist functionality
if ( WISHLIST_FUNCTIONALITY_ENABLED ) {
	$wishlistFunctionality = new \Classes\Core\Wishlist();
}

// Recently viewed products
// $recentlyViewdProducts = new \Classes\Core\RecentlyViewed();

/**
 * Lazyloaded product loop image
 */
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
add_action( 'woocommerce_before_shop_loop_item_title', 'ap_wp_theme_woocommerce_template_loop_product_thumbnail', 10 );
function ap_wp_theme_woocommerce_template_loop_product_thumbnail() {

	global $post;
	$imgID   = get_post_thumbnail_id( $post->ID );
	$alt     = get_post_meta( $imgID, '_wp_attachment_image_alt', true );
	$imgAtts = wp_get_attachment_image_src( $imgID, 'shop_catalog' );

	if ( $imgAtts ) :

		?>
		<figure class="group overflow-hidden relative">
			<img class="group-hover:scale-110 transition-all duration-200 w-full block" loading="lazy" alt="<?php echo $alt; ?>" srcset="<?php echo $imgAtts[0]; ?> 1x, <?php echo wr2x_get_retina_from_url( $imgAtts[0] ); ?> 2x" src="<?php echo $imgAtts[0]; ?>" width="<?php echo $imgAtts[1]; ?>" height="<?php echo $imgAtts[2]; ?>">
			<?php
			if ( get_field( 'mostra_icona_bio', $post->ID ) ) :
				?>
				<div class="absolute top-hgap right-hgap block z-[9]">
					<?php ap_svg( 'bio', '', 'w-[60px] h-[40px] transition-all duration-300' ); ?>
				</div>
				<?php
			endif;
			if ( get_field( 'mostra_icona_bio', $post->ID ) || get_field( 'icona_varieta', $post->ID ) ) :
				?>
			<div class="absolute top-hgap right-hgap z-[9] flex flex-col items-start justify-start gap-gap">
				<?php if ( get_field( 'mostra_icona_bio', $post->ID ) ) : ?>
					<?php
					ap_svg( 'bio', '', 'w-[60px] h-[40px] transition-all duration-300' );
				endif;
				if ( get_field( 'icona_varieta', $post->ID ) ) :
					?>
					<img class="w-[60px] h-[60px]" src="<?php echo get_stylesheet_directory_uri() . '/assets/images/' . get_field( 'icona_varieta', $post->ID ); ?>.svg" alt="">
					<?php
				endif;
				?>
			</div>
				<?php
		endif;
			?>
		</figure>
		<?php

	endif;
}

function ap_wp_get_woocommerce_product_categories_with_products() {
	// Fetch all product categories that have at least one product
	$args = array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => true,  // Only show categories with at least one product
	);

	// Get all categories
	$product_categories = get_terms( $args );

	return $product_categories;
}
/**
 * Modded catalog sorting
 */
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
add_action( 'woocommerce_before_shop_loop', 'ap_wp_theme_woocommerce_catalog_ordering', 30 );
function ap_wp_theme_woocommerce_catalog_ordering() {
	?>
	<div class="flex flex-col mdd:gap-hgap md:flex-row md:items-center justify-start mt-svgap md:mt-vgap">
		<div class="flex items-center justify-start">
			<span class="leading-[24px] block mr-hhgap py-hhgap"><?php _e( 'Ordina:', 'ap-wp-theme' ); ?></span>
			<?php woocommerce_catalog_ordering(); ?>
		</div>
		<?php
		// Get all categories with at least one product
		$product_categories = ap_wp_get_woocommerce_product_categories_with_products();

		// Get the WooCommerce shop page URL
		$shop_page_url = get_permalink( wc_get_page_id( 'shop' ) );

		// Get the current page ID
		$current_page_id = get_queried_object_id();

		// Determine if we are on the shop page
		$is_shop_page = is_shop();

		// Start outputting the list
		echo '<ul class="md:ml-auto flex gap-2gap">';

		// "All Products" entry
		$all_products_class = $is_shop_page ? 'font-bold' : '';
		echo '<li class="' . esc_attr( $all_products_class ) . '">';
		echo '<a href="' . esc_url( $shop_page_url ) . '">' . __( 'All products', 'woocommerce' ) . '</a>';
		echo '</li>';

		// Check if we have any categories to display
		if ( ! empty( $product_categories ) ) {
			foreach ( $product_categories as $category ) {
				// Determine if the current category is being viewed
				$is_current_category = ( $current_page_id == $category->term_id );

				// Set the CSS class for the category item
				$class = $is_current_category ? 'font-bold' : '';

				// Display the category
				echo '<li class="' . esc_attr( $class ) . '">';
				echo '<a href="' . esc_url( get_term_link( $category ) ) . '">' . esc_html( $category->name ) . '</a>';
				echo '</li>';
			}
		} else {
			echo '<p>No categories available with products.</p>';
		}

		echo '</ul>';
		?>
	</div>
	<?php
}
add_filter( 'woocommerce_catalog_orderby', 'ap_wp_theme_woocommerce_catalog_orderby' );
function ap_wp_theme_woocommerce_catalog_orderby( $orderby ) {
	$orderby['menu_order'] = __( 'Predefinito', 'ap-wp-theme' );
	$orderby['price']      = __( 'Prezzo', 'ap-wp-theme' );
	return $orderby;
}

add_action( 'woocommerce_before_shop_loop_item_title', 'ap_wp_theme_woocommerce_before_shop_loop_item_title' );
function ap_wp_theme_woocommerce_before_shop_loop_item_title() {
	echo '<div class="flex flex-col md:flex-row md:items-baseline md:justify-between gap-hgap md:gap-gap mt-gap">';
}
add_action( 'woocommerce_after_shop_loop_item_title', 'ap_wp_theme_woocommerce_after_shop_loop_item_title' );
function ap_wp_theme_woocommerce_after_shop_loop_item_title() {
	echo '</div>';
	echo '<div class="text-sm">';
	echo '<div class="mt-hgap">' . wp_kses_post( get_the_excerpt() ) . '</div>';
	echo '</div>';
	echo '<span class="mt-gap block transform transition-transform duration-300 hover:translate-x-2">';
	ap_svg( 'arrow-long', '', 'w-[90px] h-[17px] fill-none stroke-black hover:stroke-primary transition-all duration-300 mt-hgap' );
	echo '</span>';
}


/**
 * Change several of the breadcrumb defaults
 */
add_filter( 'woocommerce_breadcrumb_defaults', 'jk_woocommerce_breadcrumbs' );
function jk_woocommerce_breadcrumbs() {
	return array(
		'delimiter'   => ' ' . ap_svg( 'arrow-right', '', ' breadcrumb-sep w-[11px] h-[7px] stroke-black inline-block', true ) . ' ',
		'wrap_before' => '<nav class="woocommerce-breadcrumb" itemprop="breadcrumb">',
		'wrap_after'  => '</nav>',
		'before'      => '',
		'after'       => '',
		// 'home'        => _x( 'Home', 'breadcrumb', 'woocommerce' ),
	);
}

/**
 * Replace the home link URL
 */
add_filter( 'woocommerce_breadcrumb_home_url', 'woo_custom_breadrumb_home_url' );
function woo_custom_breadrumb_home_url() {
	return get_permalink( get_option( 'page_on_front' ) );
}


/**
 * Remove archive add to cart
 */
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

add_filter( 'woocommerce_variable_sale_price_html', 'ap_wp_greis_custom_variable_price_format', 10, 2 );
add_filter( 'woocommerce_variable_price_html', 'ap_wp_greis_custom_variable_price_format', 10, 2 );
function ap_wp_greis_custom_variable_price_format( $price, $product ) {
	global $woocommerce_loop;

	if ( is_product() && array_key_exists( 'loop', $woocommerce_loop ) && $woocommerce_loop['loop'] == 1 ) {
		return $price;
	}

	$prices            = $product->get_variation_prices( true );
	$min_price         = current( $prices['price'] );
	$min_regular_price = current( $prices['regular_price'] );

	if ( $min_price !== $min_regular_price ) {
		$price = sprintf( __( 'da %1$s', 'woocommerce' ), wc_price( $min_price ) );
	} else {
		$price = sprintf( __( 'da %1$s', 'woocommerce' ), wc_price( $min_price ) );
	}
	return $price;
}

add_filter( 'woocommerce_pagination_args', 'ap_wp_greis_custom_pagination_arrows' );
function ap_wp_greis_custom_pagination_arrows( $args ) {

	$args['prev_text'] = '<button class="icon-button">' . ap_svg( 'arrow-right', __( 'Prev page', 'ap-wp-theme' ), 'rem:w-[16px] rem:h-[16px] fill-none stroke-black -rotate-180 transition-all duration-500', true ) . '</button>';
	$args['next_text'] = '<button class="icon-button">' . ap_svg( 'arrow-right', __( 'Next page', 'ap-wp-theme' ), 'rem:w-[16px] rem:h-[16px] fill-none stroke-black transition-all duration-500', true ) . '</button>';

	return $args;
}


function ap_wp_is_wc_attribute() {

	/**
	 * Attributes are proper taxonomies, therefore first thing is
	 * to check if we are on a taxonomy page using the is_tax().
	 * Also, a further check if the taxonomy_is_product_attribute
	 * function exists is necessary, in order to ensure that this
	 * function does not produce fatal errors when the WooCommerce
	 * is not  activated
	 */
	if ( is_tax() && function_exists( 'taxonomy_is_product_attribute' ) ) {
		// now we know for sure that the queried object is a taxonomy
		$tax_obj = get_queried_object();
		return taxonomy_is_product_attribute( $tax_obj->taxonomy );
	}
	return false;
}

function ap_wp_fake_coupon_html( $checkout = true ) {
	$classes = '';
	if ( $checkout ) {
		$classes = 'form-row form-row-wide ';
	}
	?>
	<section class="<?php echo $classes; ?>my-gap py-gap">
		<span class="font-semibold block mb-gap"><?php echo __( 'Hai un codice sconto?', 'ap-wp-theme' ); ?></span>
		<div class="coupon flex items-center gap-hhgap">
			<input type="text" x-model="couponText" name="coupon_code_fake" class="input-text base-input !rounded-r-none flex-1" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" value="" />
			<button @click="applyCoupon" type="button" class="button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><?php esc_html_e( 'Apply', 'woocommerce' ); ?></button>
		</div>
		<div x-cloack class="flex items-center justify-start mt-2gap gap-gap" :class="coupons.length < 1 ? 'hidden' : ''" data-fake-coupons-container>
			<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
				<button data-remove="<?php echo $code; ?>" class="text-sm font-semibold bg-white leading-[14.4px] px-hgap py-[2px] flex items-center" data-coupon-remove type="button"><?php echo $code; ?> <?php ap_svg( 'close-outline', '', 'h-[10px] w-[10px] stroke-2 stroke-current fill-current ml-hgap' ); ?></button>
			<?php endforeach; ?>
		</div>
	</section>
	<?php
}


function ap_wp_theme_payment_cards() {
	?>
	<div class="mt-2gap w-[400px] max-w-full">
		<p class="text-sm mb-gap"><?php echo __( 'Accettiamo', 'ap-wp-theme' ); ?>:</p>
		<div class="flex justify-between gap-gap">
			<?php ap_svg( 'card-paypal', '', 'h-[26px] w-[44px]' ); ?>
			<?php ap_svg( 'card-stripe', '', 'h-[26px] w-[44px]' ); ?>
			<?php ap_svg( 'card-mastercard', '', 'h-[26px] w-[44px]' ); ?>
			<?php ap_svg( 'card-amex', '', 'h-[26px] w-[44px]' ); ?>
			<?php ap_svg( 'card-visa', '', 'h-[26px] w-[44px]' ); ?>
		</div>
	</div>
	<?php
}

// base product category same base shop Page for woocommerce
add_filter(
	'rewrite_rules_array',
	function ( $rules ) {
		$new_rules = array();
		$terms     = get_terms(
			array(
				'taxonomy'   => 'product_cat',
				'post_type'  => 'product',
				'hide_empty' => false,
			)
		);
		if ( $terms && ! is_wp_error( $terms ) ) {
			$siteurl = esc_url( home_url( '/' ) );
			foreach ( $terms as $term ) {
				$term_slug = $term->slug;
				$baseterm  = str_replace( $siteurl, '', get_term_link( $term->term_id, 'product_cat' ) );

				$new_rules[ $baseterm . '?$' ]                                    = 'index.php?product_cat=' . $term_slug;
				$new_rules[ $baseterm . 'page/([0-9]{1,})/?$' ]                   = 'index.php?product_cat=' . $term_slug . '&paged=$matches[1]';
				$new_rules[ $baseterm . '(?:feed/)?(feed|rdf|rss|rss2|atom)/?$' ] = 'index.php?product_cat=' . $term_slug . '&feed=$matches[1]';

			}
		}
		return $new_rules + $rules;
	}
);
