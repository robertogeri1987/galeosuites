<?php
/**
 * Wishlist functionality
 *
 * @author Alessio Pangos
 */
namespace Classes\Core;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Wishlist {

	protected static $Initialized = false;
	protected static $Cookie;
	protected static $CookieSet;

	public function __construct() {

		if ( ! self::$Initialized ) {
			self::Initialize();
		}
	}

	public static function EnqueueScripts() {

		wp_localize_script(
			'ap-wp-theme-tw-script',
			'likeParams',
			array(
				'root_url'  => admin_url( 'admin-ajax.php' ),
				'nonce'     => wp_create_nonce( 'wishlist' ),
				'logged_in' => is_user_logged_in(),
				'userId'    => get_current_user_id(),
			)
		);
	}

	public static function GetCurrentWishlist() {

		if ( is_user_logged_in() ) {

			$userId          = get_current_user_id();
			$currentWishList = get_user_meta( $userId, 'wishlist_post_ids', true );
			if ( ! is_array( $currentWishList ) ) {
				$currentWishList = array();
			}
		} else {

			self::$CookieSet ? $currentWishList = explode( ',', self::$Cookie ) : $currentWishList = array();

		}

		return $currentWishList;
	}

	public static function LikeHtml() {

		$currentWishList = self::GetCurrentWishlist();

		$id        = get_the_ID();
		$likeCount = get_post_meta( $id, 'like_count', true );

		in_array( $id, $currentWishList ) ? $existStatus = 'yes' : $existStatus = '';

		$isWishlistPage = is_page_template( 'templates/wishlist-template.php' );

		?>
			<div class="wishlist__box absolute top-hgap right-hgap" x-data="{hovering: false}" @mouseenter="hovering = true" @mouseleave="hovering = false" data-wishlist-box data-all-likes="<?php echo $likeCount; ?>" data-like="" data-prod-id="<?php echo $id; ?>" data-exists="<?php echo $existStatus; ?>" data-remove-div="<?php echo $isWishlistPage ? 'yes' : 'no'; ?>">
				<span class="relative" x-ref="icon">
					<?php
					ap_svg( 'heart-outline', __( 'Like', 'ap_wp_theme' ), 'w-[22px] h-[22px] fill-black rounded-full transition-all duration-300 ease-in-out', null, ' data-wishlist-icon-like' );
					?>
					<?php $isWishlistPage ? ap_svg( 'heart-dislike', __( 'Liked', 'ap_wp_theme' ), 'w-[22px] h-[22px] fill-black rounded-full absCenter invisible opacity-0 transition-all duration-300 ease-in-out', null, ' data-wishlist-icon-liked' ) : ap_svg( 'heart', __( 'Liked', 'ap_wp_theme' ), 'absCenter w-[22px] h-[22px] fill-black rounded-full transition-all duration-300 ease-in-out absolute invisible opacity-0', null, ' data-wishlist-icon-liked' ); ?>
				</span>
				<span class="wishlist__count" data-wishlist-count><?php echo $likeCount; ?></span>
				<div class="wishlist__tooltip" x-anchor.offset.10="$refs.icon" x-show="hovering">
					<span class="block bg-dark/70 font-bold text-white text-center px-hgap py-hhgap rounded-md min-w-[130px] text-sm">
						<?php
						if ( $existStatus !== 'yes' ) {
							_e( 'Aggiungi ai Preferiti', 'ap-wp-theme' );
						} else {
							_e( 'Rimuovi dai Preferiti', 'ap-wp-theme' );
						}
						?>
					</span>
				</div>
			</div>
		<?php
	}

	public static function ManageLike() {

		if ( ! wp_verify_nonce( $_REQUEST['_nonce'], 'wishlist' ) ) {
			die( 'Not authorized' );
		}

		$prodId      = sanitize_text_field( $_REQUEST['prodid'] );
		$postCount   = get_post_meta( $prodId, 'like_count', true );
		$requestType = sanitize_text_field( $_REQUEST['manageType'] );

		if ( ! $postCount ) {
			$postCount = 0;
		}

		if ( $requestType === 'post' ) {
			$postCount += 1;
		}

		if ( $requestType === 'delete' ) {
			$postCount -= 1;
			if ( $postCount < 0 ) {
				$postCount = 0;
			}
		}
		update_post_meta( $prodId, 'like_count', $postCount );

		if ( is_user_logged_in() ) {

			$userId          = $_REQUEST['userId'];
			$currentWishList = get_user_meta( $userId, 'wishlist_post_ids', true );

			if ( $requestType === 'post' ) {

				if ( ! is_array( $currentWishList ) ) {
					$currentWishList = array( $prodId );
					update_user_meta( $userId, 'wishlist_post_ids', $currentWishList );
					echo count( $currentWishList );
				}

				array_push( $currentWishList, $prodId );
				$currentWishList = array_unique( $currentWishList );
				update_user_meta( $userId, 'wishlist_post_ids', $currentWishList );
				echo count( $currentWishList );

			}

			if ( $requestType === 'delete' ) {

				$currentWishList = array_unique( $currentWishList );
				$currentWishList = \array_diff( $currentWishList, array( $prodId ) );
				update_user_meta( $userId, 'wishlist_post_ids', $currentWishList );
				echo count( $currentWishList );

			}
		}

		die();
	}

	public static function GetCount() {

		if ( is_user_logged_in() ) {
			$userId          = get_current_user_id();
			$currentWishList = get_user_meta( $userId, 'wishlist_post_ids', true );
			if ( ! is_array( $currentWishList ) ) {
				return '0';
			}
			return count( $currentWishList );
		}

		if ( self::$CookieSet ) {
			if ( self::$Cookie ) {
				$cookie = explode( ',', self::$Cookie );
				return count( $cookie );
			} else {
				return '0';
			}
		}
		return '0';
	}

	protected static function Initialize() {

		if ( array_key_exists( 'ap_wp_wishlist', $_COOKIE ) ) {
			self::$Cookie = $_COOKIE['ap_wp_wishlist'];
		} else {
			self::$Cookie = null;
		}
		self::$CookieSet = isset( self::$Cookie );

		add_action( 'wp_enqueue_scripts', '\Classes\Core\WishList::EnqueueScripts' );
		add_action( 'enqueue_block_editor_assets', '\Classes\Core\WishList::EnqueueScripts' );
		add_action( 'woocommerce_before_shop_loop_item', '\Classes\Core\WishList::LikeHtml' );

		add_action( 'wp_ajax_wishlist_manage_like', '\Classes\Core\WishList::ManageLike' );
		add_action( 'wp_ajax_nopriv_wishlist_manage_like', '\Classes\Core\WishList::ManageLike' );

		self::$Initialized = true;
	}
}
