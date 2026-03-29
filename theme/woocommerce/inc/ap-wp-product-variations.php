<?php
/**
 *
 * @author Alessio Pangos
 * Variation swatches functionality for WooCommerce
 * @version: 1.0
 * @license GPLv3
 */
add_action( 'woocommerce_single_product_summary', 'ap_wp_product_variations', 25 );
function ap_wp_product_variations( $varType = 'select' ) {
	global $product;

	// Per box variazioni che cambiano al click, sostituire 'select' con qualunque altro valore
	$varType = 'select';
	$counter = 0;

	if ( $product->is_type( 'variable' ) ) :

		$attributes = $product->get_variation_attributes();

		?>
		<span class="block mt-auto"><?php echo __( 'Seleziona le opzioni', 'ap-wp-theme' ); ?></span>
		<?php

		foreach ( $attributes as $attribute_name => $options ) :
			$isSelected = false;

			// Get selected value.
			if ( $attribute_name && $product instanceof WC_Product ) {
				$selected_key = 'attribute_' . sanitize_title( $attribute_name );
				// phpcs:disable WordPress.Security.NonceVerification.Recommended
				$isSelected = isset( $_REQUEST[ $selected_key ] ) ? wc_clean( wp_unslash( $_REQUEST[ $selected_key ] ) ) : $product->get_variation_default_attribute( $attribute_name );
				// phpcs:enable WordPress.Security.NonceVerification.Recommended
			}

			$class = 'grid grid-cols-6 gap-hhgap';

			if ( $varType === 'select' ) {
				$class = 'grid';
			}

			?>

			<div x-data="wcvar" class="<?php echo $class; ?> mt-hgap">
				<?php
				if ( $varType === 'select' ) {
					?>
					<select data-wcvarselect="true" x-ref="selectRef" @input="setActive()" data-minimum-results-for-search="8" data-placeholder="<?php echo wc_attribute_label( $attribute_name ); ?>">
						<option disabled><?php echo wc_attribute_label( $attribute_name ); ?></option>
					<?php
				}
				if ( $product && taxonomy_exists( $attribute_name ) ) :
					// Get terms if this is a taxonomy - ordered. We need the names too.
					$terms = wc_get_product_terms(
						$product->get_id(),
						$attribute_name,
						array(
							'fields' => 'all',
						)
					);

					$html  = '';
					$count = 1;

					foreach ( $terms as $term ) :
						if ( in_array( $term->slug, $options, true ) ) :
							if ( $varType === 'select' ) :
								?>
								<option data-attrname="<?php echo 'attribute_' . $attribute_name; ?>" data-name="<?php echo esc_attr( $term->slug ); ?>" data-index="<?php echo $count; ?>"  value="<?php echo esc_attr( $term->slug ); ?>" <?php echo $isSelected === esc_attr( $term->slug ) ? 'selected' : ''; ?>>
									<?php echo esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name, $term, $attribute_name, $product ) ); ?>
								</option>
								<?php
							else :
								?>
								<button x-init="if ('<?php echo $isSelected; ?>' === '<?php echo esc_attr( $term->slug ); ?>') {setActive($el)}" @click="setActive($el)" data-attrname="<?php echo 'attribute_' . $attribute_name; ?>" data-name="<?php echo esc_attr( $term->slug ); ?>" data-index="<?php echo $count; ?>" class="aspect-square border border-black" :class="active === $el.dataset.index ? 'bg-primary text-white pointer-events-none' : ''">
									<?php echo esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name, $term, $attribute_name, $product ) ); ?>
								</button>
								<?php
							endif;
						endif;
						++$count;
					endforeach;
				endif;
				if ( $varType === 'select' ) {
					?>
					</select>
					<?php
				}
				?>
			</div>
			<?php
			++$counter;
		endforeach;

	endif;
}
