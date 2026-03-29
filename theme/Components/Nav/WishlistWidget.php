<?php
/**
 * WishlistWidget Component
 *
 * @author Alessio Pangos
 */
namespace Components\Nav;

// If menu file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class WishlistWidget {

	public function __construct( $nav ) {

		if ( WISHLIST_FUNCTIONALITY_ENABLED ) :
			$url = get_permalink( get_field( 'id_pagina_wishlist', 'options' ) );
			?>
			<li class="desk:h-full flex items-center justify-center mr-2gap">
				<a itemprop="url" href="<?php echo $url; ?>" class="relative h-full">
					<?php echo ap_svg( 'wishlist', __( 'Wishlist', 'ap-wp-theme' ), 'fill-none rem:w-[14px] rem:h-[16px] transition-all duration-500 stroke-[var(--menuColor)] hover:stroke-primary' ); ?>
					<span data-wishlist-widget-count class="text-xs text-center rem:top-[5px] rem:right-[10px] hidden rem:w-[14px] rem:h-[16px] text-white bg-primary rounded-full"><?php echo \Classes\Core\Wishlist::GetCount(); ?></span>
				</a>
			</li>
			<?php
		endif;
	}
}
