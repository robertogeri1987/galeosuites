<?php
/**
 * Ajax submit endpoint for woocommerce reviews
 */
function ap_wp_greis_cart_update_ajax() {
	if ( 'POST' !== $_SERVER['REQUEST_METHOD'] ) {
		$protocol = $_SERVER['SERVER_PROTOCOL'];
		if ( ! in_array( $protocol, array( 'HTTP/1.1', 'HTTP/2', 'HTTP/2.0', 'HTTP/3' ), true ) ) {
			$protocol = 'HTTP/1.0';
		}

		header( 'Allow: POST' );
		header( "$protocol 405 Method Not Allowed" );
		header( 'Content-Type: text/plain' );
		exit;
	}
	if ( ! wp_verify_nonce( sanitize_text_field( $_POST ['_nonce'] ), 'cart_nonce' ) ) {
		die( 'Not authorized' );
	}

	ap_wp_greis_cart_update();

	die();
}
add_action( 'wp_ajax_ap_wp_greis_cart_update', 'ap_wp_greis_cart_update_ajax' );
add_action( 'wp_ajax_nopriv_ap_wp_greis_cart_update', 'ap_wp_greis_cart_update_ajax' );

function ap_wp_greis_cart_update() {
	?>
	<form x-data="woominicart" data-wc-mini-cart class="woocommerce-cart-form px-3gap py-2gap h-[calc(100%-80px)] overflow-y-scroll" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
		<span class="xCenter bottom-0 w-full z-[99] bg-white/50 h-[calc(100%-80px)]" :class="loadingCart ? 'pointer-events-all' : 'pointer-events-none'" x-show="loadingCart"></span>
		<section class="shop_table shop_table_responsive cart woocommerce-cart-form__contents grid grid-cols-1 gap-2gap" cellspacing="0">
			<?php
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
				/**
				 * Filter the product name.
				 *
				 * @since 2.1.0
				 * @param string $product_name Name of the product in the cart.
				 * @param array $cart_item The product in the cart.
				 * @param string $cart_item_key Key for the product in the cart.
				 */
				$product_name = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
					$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );

					if ( $_product->is_sold_individually() ) {
						$min_quantity = 1;
						$max_quantity = 1;
					} else {
						$min_quantity = 0;
						$max_quantity = $_product->get_max_purchase_quantity();
					}

					$product_quantity = woocommerce_quantity_input(
						array(
							'input_name'   => "cart[{$cart_item_key}][qty]",
							'input_value'  => $cart_item['quantity'],
							'max_value'    => $max_quantity,
							'min_value'    => $min_quantity,
							'product_name' => $product_name,
						),
						$_product,
						false
					);
					?>
					<div class="woocommerce-cart-form__cart-item grid grid-cols-6 gap-gap auto-rows-min <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>"  x-data="{
						quantity: <?php echo $cart_item['quantity'] ? $cart_item['quantity'] : 0; ?>,
						maxVal: <?php echo 0 < $max_quantity ? $max_quantity : 0; ?>,
						minVal: <?php echo $min_quantity ? $min_quantity : 0; ?>,
						remove() {
							this.quantity = 0;
							const btn = document.querySelector('[name=\'update_cart\']');
							btn.disabled = false;
							let timeout;
							if ( timeout !== undefined ) {
								clearTimeout( timeout );
							}

							timeout = setTimeout(function() {
								const btn = document.querySelector('[name=\'update_cart\']');
								btn.disabled = false;
								if ( btn  ) {
									btn.click();
								}
							}, 500 );
						},
						increase() {
							if (this.quantity < this.maxVal || this.maxVal === '') {
								this.quantity++;
								let timeout;
								if ( timeout !== undefined ) {
									clearTimeout( timeout );
								}

								timeout = setTimeout(function() {
									const btn = document.querySelector('[name=\'update_cart\']');
									btn.disabled = false;
									if ( btn  ) {
										btn.click();
									}
								}, 500 );
							}
						},
						decrease() {
							if (this.quantity > this.minVal && this.quantity > 1) {
								this.quantity--;
								let timeout;

								if ( timeout !== undefined ) {
									clearTimeout( timeout );
								}

								timeout = setTimeout(function() {
									const btn = document.querySelector('[name=\'update_cart\']');
									btn.disabled = false;
									if ( btn  ) {
										btn.click();
									}
								}, 500 );
							}
						}
					}">

						<figure class="product-thumbnail col-span-2 relative">
							<?php
							$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

							if ( ! $product_permalink ) {
								echo $thumbnail; // PHPCS: XSS ok.
							} else {
								printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
							}
							$p = wc_get_product( $product_id );
							if ( $p && $p->post_type === 'product_variation' ) {
								$p = wc_get_product( $p->get_parent_id() );
							}
							ap_wp_greis_loop_genetica( $p, 'absolute block z-[2] top-gap left-gap flex-col gap-gap md:hidden' )
							?>
						</figure>

						<div class="col-span-4 relative grid grid-cols-1 auto-rows-min">
							<div class="product-name--smaller" data-title="<?php esc_attr_e( 'Product', 'woocommerce' ); ?>">
								<?php
								if ( ! $product_permalink ) {
									echo wp_kses_post( $product_name . '&nbsp;' );
								} else {
									/**
									 * This filter is documented above.
									 *
									 * @since 2.1.0
									 */
									echo apply_filters( 'woocommerce_cart_item_name', sprintf( '<a class="text-sm font-bold" href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key );
								}

								do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

								// Meta data.
								echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.

								// Backorder notification.
								if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
									echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>', $product_id ) );
								}
								?>
							</div>
							<div class="product-remove absolute right-0 top-0 z-[2] grid grid-cols-1 gap-gap">
								<?php
									echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										'woocommerce_cart_item_remove_link',
										sprintf(
											'<a data-remove-link href="%s" class="remove icon-button icon-button--small relative" aria-label="%s" data-product_id="%s" data-product_sku="%s" @click.prevent="remove">%s</a>',
											esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
											/* translators: %s is the product name */
											esc_attr( sprintf( __( 'Remove %s from cart', 'woocommerce' ), wp_strip_all_tags( $product_name ) ) ),
											esc_attr( $product_id ),
											esc_attr( $_product->get_sku() ),
											ap_svg( 'close-outline', '', 'w-[8px] h-[8px] stroke-current absCenter transition-all duration-300 ease-in-out', true )
										),
										$cart_item_key
									);
								?>
							</div>
							<div class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'woocommerce' ); ?>">
								<p class="small-text mt-0 mb-hgap"><?php esc_attr_e( 'Quantity', 'woocommerce' ); ?>:</p>
								<?php
								echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
								?>
							</div>
							<div class="product-price hidden" data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>">
								<?php
									echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
								?>
							</div>
							<div class="product-subtotal" data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>">
								<?php
									echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
								?>
							</div>
						</div>
					</div>
					<?php
				}
			}
			?>

			<?php do_action( 'woocommerce_cart_contents' ); ?>

			<button type="submit" class="hidden button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>"><?php esc_html_e( 'Update cart', 'woocommerce' ); ?></button>

			<?php do_action( 'woocommerce_cart_actions' ); ?>

			<?php
			/**
			 * Upadate nonce function, see https://stackoverflow.com/questions/36321239/woocommerce-updating-cart-from-checkout-page-redirects-to-cart-page
			 */
			wp_nonce_field( 'woocommerce-cart', '_wpnonce', false, true );
			?>
		</section>
		<?php
		wc_get_template( 'cart/cart-totals-minicart.php' );
		?>
	</form>
	<?php
}

add_action( 'woocommerce_after_cart_totals', 'ap_wp_theme_payment_cards' );
