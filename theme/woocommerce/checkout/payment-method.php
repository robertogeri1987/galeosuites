<?php
/**
 * Output a single payment method
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/payment-method.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://woo.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<li class="bg-white !p-gap flex items-start justify-start border text-sm wc_payment_method payment_method_<?php echo esc_attr( $gateway->id ); ?>" :class="gateway == '<?php echo esc_attr( $gateway->id ); ?>' ? 'border-primary' : 'border-white cursor-pointer'"">
	<input x-model="gateway" id="payment_method_<?php echo esc_attr( $gateway->id ); ?>" type="radio" class="input-radio hidden" name="payment_method" value="<?php echo esc_attr( $gateway->id ); ?>" <?php checked( $gateway->chosen, true ); ?> data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>" />
	<div class="rounded-full min-w-[20px] w-[20px] h-[20px] bg-light flex items-center justify-center mr-gap" @click="$refs.label_<?php echo esc_attr( $gateway->id ); ?>.click()">
		<div class="rounded-full min-w-[10px] w-[10px] h-[10px]" :class="gateway == '<?php echo esc_attr( $gateway->id ); ?>' ? 'bg-dark' : 'bg-light'"></div>
	</div>
	<div class="flex-1 flex flex-col items-start justify-center gap-hgap">
		<label x-ref="label_<?php echo esc_attr( $gateway->id ); ?>" class="w-full font-semibold flex items-center justify-start mr-gap" for="payment_method_<?php echo esc_attr( $gateway->id ); ?>">
			<?php echo $gateway->get_title(); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?> <?php echo $gateway->get_icon(); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?>
		</label>
		<?php if ( $gateway->has_fields() || $gateway->get_description() ) : ?>
			<div class="payment_box payment_method_<?php echo esc_attr( $gateway->id ); ?>" <?php if ( ! $gateway->chosen ) : /* phpcs:ignore Squiz.ControlStructures.ControlSignature.NewlineAfterOpenBrace */ ?>style="display:none;"<?php endif; /* phpcs:ignore Squiz.ControlStructures.ControlSignature.NewlineAfterOpenBrace */ ?>>
				<?php $gateway->payment_fields(); ?>
			</div>
		<?php endif; ?>
	</div>
</li>
