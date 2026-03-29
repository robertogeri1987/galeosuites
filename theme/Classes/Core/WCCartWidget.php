<?php
/**
 * Outputs the Woocommerce Cart
 *
 * @author Alessio Pangos
 */
namespace Classes\Core;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class WCCartWidget {

	public static function ReturnWcCart() {
		global $woocommerce;

		$cart_item = array(
			'cart_url'            => wc_get_cart_url(),
			'cart_contents_count' => $woocommerce->cart->get_cart_contents_count(),
			'cart_total'          => WC()->cart->get_cart_total(),
			'cart_name'           => __( '', 'ap-wp-theme' ),
		);

		?>
		<li data-nav-cart class="navigation__item navigation-item--special navigation__item--cart inline-block mdd:w-full md:h-full">
			<a class="cart-widget__contents navigation__link relative flex items-center h-full pr-hhgap" href="<?php echo $cart_item['cart_url']; ?>">
				<?php echo ap_svg( 'cart', $cart_item['cart_name'], 'rem:w-[27px] rem:h-[27px] fill-white', true ); ?>
				<span class="cart-widget__count absolute text-xs text-center mobile:top-0 rem:top-[5px] mobile:rem:left-[17px] desk:rem:right-[10px] block rem:w-[16px] rem:h-[16px] text-white bg-primary rounded-full"><?php echo $cart_item['cart_contents_count']; ?></span>
				<span class="sr-only"><?php echo $cart_item['cart_name']; ?></span>
			</a>
		</li>
		<?php
	}
}
