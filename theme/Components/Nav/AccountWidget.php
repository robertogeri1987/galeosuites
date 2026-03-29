<?php
/**
 * AccountWidget Component
 *
 * @author Alessio Pangos
 */
namespace Components\Nav;

// If menu file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class AccountWidget {

	public function __construct( $nav ) {

		if ( is_user_logged_in() ) :
			?>
			<li class="mdd:w-full md:h-full mr-2gap text-[var(--menuColor)] hover:text-primary transition-all duration-500 flex items-center justify-center">
				<a itemprop="url" href="<?php echo get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ); ?>" class="navigation__link">
					<?php ap_svg( 'account', __( 'Account', 'ap-wp-theme' ), 'w-[15px] h-[17px] stroke-current fill-none' ); ?>
				</a>
			</li>
			<?php
		else :
			?>
			<li class="mdd:w-full md:h-full mr-2gap text-[var(--menuColor)] hover:text-primary transition-all duration-500 flex items-center justify-center">
				<a itemprop="url" href="<?php echo get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ); ?>" class="navigation__link">
					<?php ap_svg( 'account', __( 'Account', 'ap-wp-theme' ), 'w-[15px] h-[17px] stroke-current fill-none' ); ?>
				</a>
			</li>
			<?php
		endif;
	}
}
