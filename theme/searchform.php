<form method="get" data-predictive-search-form class="searchform flex items-center relative w-full max-w-full md:mb-gap" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label data-predictive-search-label class="sr-only" for="s"><?php esc_attr_e( 'Search', 'ap-wp-theme' ); ?></label>
	<input data-predictive-search-input type="text" class="base-input w-full" name="s" id="s" placeholder="<?php esc_attr_e( 'Search', 'ap-wp-theme' ); ?>" autocomplete="off"/>
	<?php if ( class_exists( 'WooCommerce' ) ) : ?>
		<input data-predictive-search-input-product type="hidden" name="post_type" value="product">
	<?php endif; ?>
	<button data-predictive-search-button type="submit" class="absolute right-hhgap opacity-0 top-1/2 transform -translate-y-1/2 p-0 bg-transparent" name="submit" id="searchsubmit">
		<?php ap_svg( 'search', __( 'Search', 'ap-wp-theme' ), 'rem:w-[20px] rem:h-[26px] fill-current hover:fill-primary transition-all duration-400' ); ?>
	</button>
</form>
<?php if ( PREDICTIVE_SEARCH_ENABLED ) : ?>
	<ul data-predictive-search-results class="flex flex-col relative z-[999] w-full h-full max-h-full overflow-auto text-black text-xs py-hhgap pointer-events-none invisible opacity-0 transition-opacity duration-500"></ul>
<?php endif; ?>
