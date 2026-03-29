<?php
/**
 * AP WP Theme
 *
 * This file adds the page header functionality to the AP WP Theme.
 *
 * @package   AP WP Theme
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

add_action( 'ap_wp_before', 'ap_wp_page_header_setup' );
/**
 * Set up page header.
 *
 * Removes and repositions the title on all possible types of pages. Wrapped
 * up into one function so it can easily be unhooked from ap_wp_before.
 *
 * @return void
 */
function ap_wp_page_header_setup() {

	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );

	// Remove search results and shop page titles.
	add_filter( 'woocommerce_show_page_title', '__return_null' );
}

/**
 * Output the post title
 */
function ap_wp_do_post_title() {

	$title = get_the_title();

	if ( '' === trim( $title ) ) {
		return;
	}

	// Wrap in H1 on singular pages.
	$wrap = is_singular() ? 'h1' : 'h2';

	echo '<' . $wrap . ' class="title-lg uppercase mx-auto text-left mb-gap">' . $title . '</' . $wrap . '>';
}

/**
 * Custom archives title
 */
add_filter(
	'get_the_archive_title',
	function ( $title ) {
		if ( is_home() ) {
			return get_the_title( get_option( 'page_for_posts' ) );
		}

		if ( is_category() ) {
			$title = single_cat_title( '', false );
		} elseif ( is_tag() ) {
			$title = single_tag_title( '', false );
		} elseif ( is_author() ) {
			$title = '<span class="vcard">' . get_the_author() . '</span>';
		} elseif ( is_tax() ) { // for custom post types
			$title = sprintf( __( '%1$s' ), single_term_title( '', false ) );
		} elseif ( is_post_type_archive() ) {
			$title = post_type_archive_title( '', false );
		}
		return $title;
	}
);

add_action( 'ap_wp_page_header', 'ap_wp_page_title', 10 );
/**
 * Display title in page header.
 *
 * Works out the correct title to display in the page header on a per page
 * basis. Also adds the entry title back in to the entry inside the loop.
 *
 * @return void
 */
function ap_wp_page_title() {

	if ( is_front_page() ) {
		return;
	}

	if ( class_exists( 'WooCommerce' ) && ( is_shop() ) ) {
		echo '<h1 class="title-lg--menu uppercase mx-auto text-left mt-svgap md:mt-vgap">' . get_the_title( wc_get_page_id( 'shop' ) ) . '</h1>';

	} elseif ( class_exists( 'WooCommerce' ) && ( is_product_category() ) ) {
		echo '<h1 class="title-lg--menu uppercase mx-auto text-left mt-svgap md:mt-vgap">' . get_the_archive_title() . '</h1>';

	} elseif ( is_home() || is_archive() || is_category() || is_tag() || is_tax() ) {

		echo '<h1 class="title-lg--menu uppercase mx-auto text-center mt-svgap md:mt-vgap mb-gap">' . get_the_archive_title() . '</h1>';
		the_archive_description( '<div itemprop="description" class="archive-description text-sm w-[415px] text-center mx-auto max-w-full mt-gap">', '</div>' );

	} elseif ( class_exists( 'WooCommerce' ) && is_search() ) {

		echo '<h1 class="title-lg--menu uppercase mx-auto text-left mt-svgap md:mt-vgap">' . __( 'Search results for: ', 'ap-wp-theme' ) . get_search_query() . '</h1>';

	} elseif ( is_search() ) {

		echo '<h1 class="title-lg--menu uppercase mx-auto text-left mt-svgap md:mt-vgap">' . __( 'Search results for: ', 'ap-wp-theme' ) . get_search_query() . '</h1>';

	} elseif ( is_404() ) {

		echo '<h1 class="title-lg--menu uppercase mx-auto text-left mt-svgap md:mt-vgap">' . __( 'Not found, error 404', 'ap-wp-theme' ) . '</h1>';

	} elseif ( is_single() || is_singular() ) {

		ap_wp_do_post_title();

	}
}

add_action( 'ap_wp_page_header', 'ap_wp_page_excerpt', 20 );
/**
 * Display page excerpt.
 *
 * Prints the correct excerpt on a per page basis. If on the WooCommerce shop
 * page then the products result count is be displayed instead of the page
 * excerpt. Also, if on a single product then no excerpt will be output.
 *
 * @return void
 */
function ap_wp_page_excerpt() {

	if ( class_exists( 'WooCommerce' ) && is_shop() ) {

		echo '<div class="text-sm w-[415px] max-w-full mt-gap">';
		echo get_the_excerpt( get_option( 'woocommerce_shop_page_id' ) );
		echo '</div>';

	} elseif ( class_exists( 'WooCommerce' ) && is_product_category() ) {

		echo '<div class="text-sm w-[415px] max-w-full mt-gap">';
		echo category_description();
		echo '</div>';

	} elseif ( is_home() ) {

		printf( '<p class="mx-auto text-center w-[598px] max-w-full" itemprop="description">%s</p>', do_shortcode( get_the_excerpt( get_option( 'page_for_posts' ) ) ) );

	} elseif ( is_search() ) {

		return;

	} elseif ( is_404() ) {

		$id = get_page_by_path( 'error' );

		if ( has_excerpt( $id ) ) {

			printf( '<p class="mx-auto text-center" itemprop="description">%s</p>', do_shortcode( get_the_excerpt( $id ) ) );

		}
	} elseif ( ( is_single() || is_singular() ) && ! is_singular( 'product' ) && has_excerpt() ) {

		printf( '<p class="mx-auto text-center" itemprop="description">%s</p>', do_shortcode( get_the_excerpt() ) );

	}
}

add_action( 'ap_wp_before_entry_content', 'ap_wp_page_header' );
/**
 * Display the page header.
 *
 * Outputs the opening and closing page header markup and runs
 * ap_wp_page_header which all of our header functions are hooked to.
 *
 * @return void
 */
function ap_wp_page_header() {
	$headerClass = '';
	if ( class_exists( 'WooCommerce' ) && ! is_cart() && ! is_checkout() || ! class_exists( 'WooCommerce' ) ) {
		$headerClass = ' pt-2gap';
	}

	?>
	<header id="page-header" class="page-header<?php echo $headerClass; ?>" role="banner">
		<?php do_action( 'ap_wp_page_header_before_wrap' ); ?>
		<?php
		$headerElements = ap_wp_theme_custom_header();
		if ( $headerElements ) :
			?>
			<div class="alignfull h-screen">
				<?php // do not lazyload above the fold images ?>
				<?php
				\Classes\Core\Utils::ResponsivePicture(
					$headerElements[0],
					'object-cover w-screen h-full object-center',
					$headerElements[2],
					$headerElements[1],
					false,
					$headerElements[3],
					$headerElements[4],
					$headerElements[5],
				);
				?>
				<div class="block absCenter layout-container z-1 text-white">
					<?php do_action( 'ap_wp_page_header' ); ?>
				</div>
			</div>
		<?php else : ?>
			<?php if ( class_exists( 'WooCommerce' ) && ! is_cart() && ! is_checkout() ) : ?>
			<div class="layout-container mx-auto">
			<?php endif; ?>
				<?php do_action( 'ap_wp_page_header' ); ?>
			<?php if ( class_exists( 'WooCommerce' ) && ! is_cart() && ! is_checkout() ) : ?>
			</div>
			<?php endif; ?>
		<?php endif; ?>
		<?php do_action( 'ap_wp_page_header_after_wrap' ); ?>
	</header>
	<?php
}

/**
 * Custom header image callback.
 *
 * Loads custom header or featured image depending on what is set on a per
 * page basis. If a featured image is set for a page, it will override
 * the default header image. It also gets the image for custom post
 * types by looking for a page with the same slug as the CPT e.g
 * the Portfolio CPT archive will pull the featured image from
 * a page with the slug of 'portfolio', if the page exists.
 *
 * @return string
 */
function ap_wp_theme_custom_header() {

	$id     = '';
	$width  = '';
	$height = '';
	$url    = '';

	// Get the current page ID.
	if ( is_post_type_archive() ) {

		$id = get_page_by_path( get_query_var( 'post_type' ) );

	} elseif ( is_front_page() ) {

		$id = get_option( 'page_on_front' );

	} elseif ( is_home() ) {

		$id = get_option( 'page_for_posts' );

	} elseif ( is_search() ) {

		add_filter( 'body_class', 'ap_no_header_image_body_class' );
		return false;

	} elseif ( is_404() ) {

		add_filter( 'body_class', 'ap_no_header_image_body_class' );
		return false;

	} elseif ( is_singular() ) {

		$id = get_the_id();

	}

	if ( class_exists( 'WooCommerce' ) && is_shop() ) {

		$id  = wc_get_page_id( 'shop' );
		$src = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'full' );
		if ( ! $src ) {
			add_filter( 'body_class', 'ap_no_header_image_body_class' );
			return false;
		}
		$url       = $src[0];
		$width     = $src[1];
		$height    = $src[2];
		$mobileImg = get_field( 'header_mobile_image', $id );
		$tabImg    = get_field( 'header_tablet_image', $id );

	} elseif ( ( is_archive() && ( class_exists( 'WooCommerce' ) && ! is_product_category() ) ) || ( is_archive() && ! class_exists( 'WooCommerce' ) ) ) {

		add_filter( 'body_class', 'ap_no_header_image_body_class' );
		return false;

	} elseif ( class_exists( 'WooCommerce' ) && is_product_category() ) {

		global $wp_query;
		$cat      = $wp_query->get_queried_object();
		$thumb_id = get_term_meta( $cat->term_id, 'thumbnail_id', true );
		$src      = wp_get_attachment_image_src( $thumb_id, 'full' );
		if ( ! $src ) {
			add_filter( 'body_class', 'ap_no_header_image_body_class' );
			return false;
		}
		$url       = $src[0];
		$width     = $src[1];
		$height    = $src[2];
		$mobileImg = get_field( 'header_mobile_image', 'product_cat_' . $cat->term_id . '' );
		$tabImg    = get_field( 'header_tablet_image', 'product_cat_' . $cat->term_id . '' );
		$id        = 'product_cat_' . $cat->term_id . '';

	} else {

		$src = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'full' );
		if ( ! $src ) {
			add_filter( 'body_class', 'ap_no_header_image_body_class' );
			return false;
		}
		$url       = $src[0];
		$width     = $src[1];
		$height    = $src[2];
		$mobileImg = get_field( 'header_mobile_image', $id );
		$tabImg    = get_field( 'header_tablet_image', $id );
	}

	if ( get_field( 'nascondi_immagine_header', $id ) ) {
		add_filter( 'body_class', 'ap_no_header_image_body_class' );
		return false;
	}

	if ( get_field( 'utilizza_slider', $id ) ) {

		remove_action( 'ap_wp_before_entry_content', 'ap_wp_page_header' );
		add_action( 'ap_wp_before_entry_content', 'ap_acf_swiper_slider' );
		function ap_acf_swiper_slider() {
			$thisSlider               = new \Classes\Core\ACFSlider( false );
			$thisSlider->lazy         = false;
			$thisSlider->screenHeight = true;
			$thisSlider->sliderType   = 'Single';
			$thisSlider->render();
		}

		return false;

	}

	$url ? $imgAlt = get_post_meta( $id, '_wp_attachment_image_alt', true ) : $imgAlt = '';

	if ( ! $url && ! is_front_page() ) {

		add_filter( 'body_class', 'ap_no_header_image_body_class' );
		return false;

	}

	if ( $url ) {
		return array( $url, $mobileImg ? $mobileImg['url'] : false, $tabImg ? $tabImg['url'] : false, $imgAlt, $width, $height );
	} else {
		add_filter( 'body_class', 'ap_no_header_image_body_class' );
		return false;
	}
}

function ap_no_header_image_body_class( $classes ) {
	$classes[] = 'no-header-image';
	return $classes;
}
