<?php
/**
 * Navigation Item Component
 *
 * @author Alessio Pangos
 */
namespace Components\Nav;

// If menu file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class NavigationItemCart {

	public function __construct( $nav ) {
		global $woocommerce;

		$cart_item = array(
			'cart_url'            => wc_get_cart_url(),
			'cart_contents_count' => $woocommerce->cart->get_cart_contents_count(),
			'cart_total'          => WC()->cart->get_cart_total(),
			'cart_name'           => __( '', 'ap-wp-theme' ),
		);

		$nav->isCurrent         = '';
		$nav->currentLink       = Utils::GetLinkAndTarget( 'link', false );
		$nav->currentLinkTarget = '';

		if ( $nav->currentLink ) {
			$nav->currentLinkTarget = $nav->currentLink['target'];
			$nav->getIsCurrent( $nav->currentLink );
		}

		$hasSubmenu = get_sub_field( 'has_submenu' );
		$addClasses = get_sub_field( 'additional_classes' );

		?>
			<li
				class="<?php echo $nav->navigationItemClasses . $addClasses; ?>"
				x-data="menuItem"
				x-ref="menuItem"
			>
				<?php
				$nav->isCurrent = '';
				if ( $nav->currentLink && array_key_exists( 'url', $nav->currentLink ) && $nav->currentLink['url'] !== '' ) :

					?>
					<a
						class="<?php echo $nav->navigationLinkClasses . $nav->isCurrent; ?>"
						href="<?php echo $nav->currentLink['url']; ?>"<?php echo $nav->currentLinkTarget; ?>
						itemprop="url"
						@click="handleClick($event)"

					>
						<?php
						the_sub_field( 'title' );
						?>
						<span class="cart-counts">
							<?php echo ( $cart_item['cart_contents_count'] > 0 ) ? '(' . $cart_item['cart_contents_count'] . ')' : ''; ?>
						</span>
					</a>
					<?php

				else :

					?>
					<span class="<?php echo $nav->navigationLinkClasses; ?>" @click="toggle()">
						<?php
						the_sub_field( 'title' );
						?>
						<span class="cart-counts">
							<?php echo ( $cart_item['cart_contents_count'] > 0 ) ? '(' . $cart_item['cart_contents_count'] . ')' : ''; ?>
						</span>
					</span>
					<?php

				endif;
				?>
			</li>
		<?php
	}
}
