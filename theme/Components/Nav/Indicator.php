<?php
/**
 * Indicator Component
 *
 * @author Alessio Pangos
 */
namespace Components\Nav;

// If menu file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Indicator {

	public function __construct() {

		if ( get_sub_field( 'has_submenu' ) ) {
			?>
			<span class="mobile:ml-auto relative block cursor-pointer w-[20px] h-[20px] desk:hidden z-[1]" x-ref="indicator">
				<?php
				ap_svg( 'arrow-right', null, ' w-[20px] min-w-[20px] h-[20px] fill-none stroke-current transition-all duration-500 pointer-events-none desk:hidden' );
				?>
			</span>
			<?php
		}
	}
}
