<?php
/**
 *
 * @author Alessio Pangos
 * Reorder checkout fields
 * @version: 1.0
 * @package: woocommerce
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

function output_payment_button() {
	$order_button_text = apply_filters( 'woocommerce_order_button_text', __( 'Place order', 'woocommerce' ) );
	echo '<input type="submit" class="base-button button alt" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '" />';
}


function remove_woocommerce_order_button_html() {
	return '';
}

add_filter( 'woocommerce_order_button_html', 'remove_woocommerce_order_button_html' );
add_action( 'woocommerce_checkout_order_review', 'output_payment_button', 20 );

add_action( 'woocommerce_review_order_before_cart_contents', 'ap_greis_checkout_summary_text', 10 );
function ap_greis_checkout_summary_text() {
	$count        = WC()->cart->get_cart_contents_count();
	$productsText = __( 'Product', 'woocommerce' );

	if ( $count > 1 ) {
		$productsText = __( 'Products', 'woocommerce' );
	}
	?>
	<tr>
		<td class="col-span-2">
			<span class="flex items-center justify-start base-text"><span class="font-bold pr-hgap"><?php echo __( 'Cart', 'woocommerce' ); ?></span> <span class="lowercase mr-2gap">(<?php echo $count . ' ' . $productsText; ?>)</span><a href="<?php echo wc_get_cart_url(); ?>" class="ml-auto underline cursor-pointer"><?php echo __( 'Modifica', 'ap-wp-theme' ); ?></a></span>
			<div class="grid grid-cols-3 gap-gap mt-gap">
				<?php
				$counter = 1;
				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
					$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

					if ( $counter < 4 && $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
						?>
						<div class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
							<figure class="product-thumbnail col-span-2 relative">
								<?php
								$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
								echo $thumbnail;
								if ( $counter === 3 && $count > 3 ) {
									?>
									<div class="absCenter w-full h-full z-[2] bg-white/70 flex items-center justify-center">
										<span class="title-sm !mb-0">
											+<?php echo $count - $counter; ?>
										</span>
									</div>
									<?php
								}
								?>
							</figure>
						</div>
						<?php
						++$counter;
					}
				}
				?>
			</div>
		</td>
	</tr>
	<?php
}

function add_summary_fragment( $fragments ) {
	ob_start();
	ap_greis_checkout_summary_text();
	$fragments['div.wc-custom-frag'] = ob_get_clean();
	return $fragments;
}
add_filter( 'woocommerce_update_order_review_fragments', 'add_summary_fragment', 999, 1 );

/**
 * @snippet       Add Inline Field Error Notifications @ WooCommerce Checkout
 * @how-to        Get CustomizeWoo.com FREE
 * @author        Rodolfo Melogli
 * @compatible    WooCommerce 5
 * @community     https://businessbloomer.com/club/
 */

// add_filter( 'woocommerce_form_field', 'bbloomer_checkout_fields_in_label_error', 10, 4 );

// function bbloomer_checkout_fields_in_label_error( $field, $key, $args, $value ) {
// $error  = '<span class="error" style="display:none">';
// $error .= sprintf( __( 'Questo campo è obbligatorio', 'ap-wp-theme' ) );
// $error .= '</span>';
// $field  = substr_replace( $field, $error, strpos( $field, '</label>' ), 0 );
// return $field;
// }

// Hook in
add_filter( 'woocommerce_checkout_fields', 'ap_wp_custom_override_checkout_fields', 99 );
// Our hooked in function - $fields is passed via the filter!
function ap_wp_custom_override_checkout_fields( $fields ) {
	$fields['billing']['billing_email']['class']          = array( 'form-row-wide' );
	$fields['billing']['billing_email']['placeholder']    = __( 'esempio@gmail.com', 'ap-wp-theme' );
	$fields['billing']['billing_email']['priority']       = 4;
	$fields['billing']['billing_postcode']['class']       = array( 'form-row-first', 'address-field' );
	$fields['billing']['billing_postcode']['label']       = __( 'Codice postale', 'ap-wp-theme' );
	$fields['billing']['billing_postcode']['placeholder'] = 'es. 00118';
	$fields['billing']['billing_state']['class']          = array( 'form-row-last', 'address-field' );
	$fields['billing']['billing_company']['label']        = __( 'Azienda', 'ap-wp-theme' );
	$fields['billing']['billing_company']['placeholder']  = '';
	$fields['billing']['billing_first_name']['class']     = array( 'form-row-wide' );
	$fields['billing']['billing_first_name']['autofocus'] = 'false';
	$fields['billing']['billing_last_name']['class']      = array( 'form-row-wide' );
	$fields['billing']['billing_phone']['class']          = array( 'form-row-wide' );
	$fields['billing']['billing_phone']['label']          = __( 'Numero di telefono', 'ap-wp-theme' );
	$fields['billing']['billing_phone']['placeholder']    = __( 'es. +39 333 33 33 333', 'ap-wp-theme' );

	$fields['shipping']['shipping_postcode']['class']      = array( 'form-row-wide', 'address-field' );
	$fields['shipping']['shipping_state']['class']         = array( 'form-row-wide', 'address-field' );
	$fields['shipping']['shipping_company']['label']       = __( 'Azienda', 'ap-wp-theme' );
	$fields['shipping']['shipping_company']['placeholder'] = '';
	$fields['shipping']['shipping_first_name']['class']    = array( 'form-row-wide' );
	$fields['shipping']['shipping_last_name']['class']     = array( 'form-row-wide' );

	return $fields;
}

add_action( 'woocommerce_form_field_text', 'ap_wp_checkout_coupon_fake_coupon_field', 10, 2 );
function ap_wp_checkout_coupon_fake_coupon_field( $field, $key ) {
	// Fake coupon field that triggers the real (hidden) coupon field
	if ( is_checkout() && ( $key == 'billing_first_name' ) ) :
		ob_start();
		ap_wp_fake_coupon_html();
		$coupon = ob_get_clean();
		$field  = $coupon . $field;
	endif;
	return $field;
}
/**
 * @snippet       Rename State Field Label @ WooCommerce Checkout
 * @how-to        Get CustomizeWoo.com FREE
 * @author        Rodolfo Melogli
 * @compatible    WooCommerce 3.6.4
 * @community     https://businessbloomer.com/club/
 *
	‘country‘
	‘first_name‘
	‘last_name‘
	‘company‘
	‘address_1‘
	‘address_2‘
	‘city‘
	‘postcode‘
 */

add_filter( 'woocommerce_default_address_fields', 'bbloomer_rename_state_province', 9999 );
function bbloomer_rename_state_province( $fields ) {
	$fields['address_1']['label'] = __( 'Indirizzo', 'ap-wp-theme' );
	$fields ['postcode']['label'] = __( 'Codice postale', 'ap-wp-theme' );
	return $fields;
}

/**
 * Remove order notes
 */
add_filter( 'woocommerce_enable_order_notes_field', '__return_false', 9999 );

add_action( 'woocommerce_after_checkout_billing_form', 'ap_wp_checkout_billing_form', 10 );
function ap_wp_checkout_billing_form() {
	?>
	<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
	<section class="mt-gap pt-gap form-row form-row-wide">
		<span class="font-semibold block mb-gap"><?php echo __( 'Opzioni di spedizione', 'ap-wp-theme' ); ?></span>
		<div data-shipping-options-container class="grid grid-cols-1 gap-gap mt-gap"></div>
	</section>
	<section class="py-gap form-row form-row-wide">
		<span class="font-semibold block mb-gap"><?php echo __( 'Indirizzo di spedizione', 'ap-wp-theme' ); ?></span>
		<div data-shipping-address-container class="grid grid-cols-1 md:grid-cols-2 gap-gap mt-gap">
			<button class="h-full" type="button">
				<div class="bg-white p-gap h-full flex items-center justify-start border transition-all duration-500" :class="!shipDifferent ? 'border-primary' : 'border-white cursor-pointer'" @click="setShipDifferent(false)">
					<div class="rounded-full min-w-[20px] w-[20px] h-[20px] bg-light flex items-center justify-center mr-gap">
						<div class="rounded-full min-w-[10px] w-[10px] h-[10px]" :class="!shipDifferent ? 'bg-dark' : 'bg-light'"></div>
					</div>
					<div class="flex-1 flex flex-col items-start justify-center gap-hgap text-left">
						<span class="text-sm"><?php echo __( 'Stesso indirizzo di spedizione', 'ap-wp-theme' ); ?></span>
					</div>
				</div>
			</button>
			<button type="button">
				<div class="bg-white p-gap flex items-center justify-start border transition-all duration-500" :class="shipDifferent ? 'border-primary' : 'border-white cursor-pointer'" @click="setShipDifferent(true)">
					<div class="rounded-full min-w-[20px] w-[20px] h-[20px] bg-light flex items-center justify-center mr-gap">
						<div class="rounded-full min-w-[10px] w-[10px] h-[10px]" :class="shipDifferent ? 'bg-dark' : 'bg-light'"></div>
					</div>
					<div class="flex-1 flex flex-col items-start justify-center gap-hgap text-left">
						<span class="text-sm"><?php echo __( 'Indirizzo di spedizione differente', 'ap-wp-theme' ); ?></span>
					</div>
				</div>
			</button>
		</div>
	</section>
	<?php endif; ?>
	<?php
}

add_action( 'woocommerce_review_order_before_payment', 'ap_wp_payment_title', 1 );
function ap_wp_payment_title() {
	?>
	<div class="mt-gap"></div>
	<span class="font-semibold block mt-2gap mb-gap"><?php echo __( 'Metodi di pagamento', 'ap-wp-theme' ); ?></span>
	<?php
}

/**
 * @snippet       Add privacy policy tick box at checkout
 * @how-to        Get CustomizeWoo.com FREE
 * @author        Rodolfo Melogli
 * @compatible    WooCommerce 3.6.3
 * @community     https://businessbloomer.com/club/
 */

add_action( 'woocommerce_review_order_before_submit', 'bbloomer_add_checkout_privacy_policy', 9 );
function bbloomer_add_checkout_privacy_policy() {

	woocommerce_form_field(
		'privacy_policy',
		array(
			'type'        => 'checkbox',
			'class'       => array( 'custom-cf7-checkboxes' ),
			'label_class' => array( 'woocommerce-form__label woocommerce-form__label-for-checkbox checkbox' ),
			'input_class' => array( 'woocommerce-form__input woocommerce-form__input-checkbox input-checkbox' ),
			'required'    => true,
			'label'       => '<span class="wpcf7-list-item-label"><span>Ho letto e compreso la <a target="_blank" href="/privacy-policy">l\'informativa sulla privacy</a> e acconsento al trattamento dei miei dati personali</span></span>',
		)
	);
}

// Show notice if customer does not tick

add_action( 'woocommerce_checkout_process', 'bbloomer_not_approved_privacy' );
function bbloomer_not_approved_privacy() {
	if ( ! (int) isset( $_POST['privacy_policy'] ) ) {
		wc_add_notice( __( 'Per favore accetta la privacy policy', 'ap-wp-theme' ), 'error' );
	}
}

remove_action( 'woocommerce_checkout_terms_and_conditions', 'wc_checkout_privacy_policy_text', 20 );
