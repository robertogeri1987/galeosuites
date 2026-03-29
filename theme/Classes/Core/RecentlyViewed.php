<?php
/**
 * Show recently viewed products
 *
 * @author Alessio Pangos
 */
namespace Classes\Core;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class RecentlyViewed {

	protected static $Initialized    = false;
	protected static $RecentlyViewed = false;

	public function __construct() {

		if ( ! self::$Initialized ) {
			self::Initialize();
		}
	}

	public static function SetCookie() {

		$post_id = url_to_postid( 'https://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] );

		if ( isset( $_COOKIE['ap_wp_rv_products'] ) && $_COOKIE['ap_wp_rv_products'] != '' ) {
			$ap_wp_rv_products = unserialize( $_COOKIE['ap_wp_rv_products'] );
			if ( ! is_array( $ap_wp_rv_products ) ) {
				$ap_wp_rv_products = array( $post_id );
			} else {
				array_unshift( $ap_wp_rv_products, $post_id );
				$ap_wp_rv_products = array_slice( array_unique( $ap_wp_rv_products ), 0, 8 );
			}
		} else {
			$ap_wp_rv_products = array( $post_id );
		}
		setcookie( 'ap_wp_rv_products', serialize( $ap_wp_rv_products ), time() + ( DAY_IN_SECONDS * 31 ), '/' );

		return;
	}

	protected static function GetRecentlyViewedProducts() {

		if ( ! is_product() ) {
			return;
		}

		global $post;

		// Get the current post id.
		$current_post_id = get_the_ID();

		if ( is_user_logged_in() ) {

			// Store recently viewed post ids in user meta.
			self::$RecentlyViewed = get_user_meta( get_current_user_id(), 'recently_viewed', true );

			if ( ! self::$RecentlyViewed ) {
				self::$RecentlyViewed = array();
			}

			// Prepend id to the beginning of recently viewed id array.(http://php.net/manual/en/function.array-unshift.php)
			array_unshift( self::$RecentlyViewed, $current_post_id );

			// Keep the recently viewed items at 5. (http://www.php.net/manual/en/function.array-slice.php)
			self::$RecentlyViewed = array_slice( array_unique( self::$RecentlyViewed ), 0, 8 ); // Extract a slice of the array

			// Update the user meta with new value.
			update_user_meta( get_current_user_id(), 'recently_viewed', self::$RecentlyViewed );

		} else {

			self::$RecentlyViewed = unserialize( $_COOKIE['ap_wp_rv_products'] );

		}
	}

	public static function OutputRecentlyViewed() {

		self::GetRecentlyViewedProducts();

		if ( self::$RecentlyViewed ) {

			$recentlyViewdProducts = new \WP_Query(
				array(
					'post_type'      => 'product',
					'posts_per_page' => 6,
					'post__in'       => self::$RecentlyViewed,
					'orderby'        => 'date',
					'order'          => 'ASC',
				)
			);

			if ( $recentlyViewdProducts->have_posts() ) {

				echo '<section class="single-product__section">';
				echo '<h2 class="single-product__section-title">';
				_e( 'Recently Viewed', 'ap-wp-theme' );
				echo '</h2>';
				echo '<div class="grid gap-hgap grid-cols-1 md-grid-cols-6">';
				while ( $recentlyViewdProducts->have_posts() ) {
					$recentlyViewdProducts->the_post();
					$alt = get_post_meta( get_post_thumbnail_id( $recentlyViewdProducts->ID ), '_wp_attachment_image_alt', true );
					?>
						<a href="<?php echo get_the_permalink(); ?>">
							<img loading="lazy" alt="<?php echo $alt; ?>" src="<?php echo get_the_post_thumbnail_url( $recentlyViewdProducts->ID, 'woocommerce_thumbnail' ); ?>">
						</a>
					<?php
				}
				echo '</div>';
				echo '</section>';

			}

			wp_reset_postdata();

		}
	}

	protected static function Initialize() {

		add_action( 'init', '\Classes\Core\RecentlyViewed::SetCookie' );
		add_action( 'woocommerce_after_single_product', '\Classes\Core\RecentlyViewed::OutputRecentlyViewed', 25 );
		self::$Initialized = true;
	}
}
