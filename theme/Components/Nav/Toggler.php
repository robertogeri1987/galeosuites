<?php
/**
 * Toggler Item Component
 *
 * @author Alessio Pangos
 */
namespace Components\Nav;

// If menu file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Toggler {

	public function __construct() {
		?>
		<button x-data class="desk:hidden block w-[20px] h-[20px] mr-gap cursor-pointer" :class="$store.header.menuOpen ? 'active' : ''" data-hamburger @click="$store.header.toggleMenuOpen()" type="button" name="button">
			<span class="sr-only">
				<?php echo __( 'Open menu', 'ap-wp-theme' ); ?>
			</span>
			<span aria-hidden="true" :class="(!$store.header.menuOpen && $store.header.isWhite && !$store.header.isScrolling && !$store.header.hasScrolled) ? 'white' : ''"></span>
		</button>
		<?php
	}
}
