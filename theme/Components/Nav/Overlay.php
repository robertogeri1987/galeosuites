<?php
/**
 * Overlay Component
 *
 * @author Alessio Pangos
 */
namespace Components\Nav;

// If menu file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Overlay {

	public function __construct() {
		?>
		<template x-teleport="body">
			<span x-cloak @click="$store.header.closeMenu()" class="fixed top-0 left-0 transition-opacity duration-[0.5s] desk:hidden" :class="$store.header.menuOpen ? 'opacity-1 w-full h-full bg-black/20 z-[20] pointer-events-auto' : 'bg-transparent opacity-0 z-[-999999] w-0 h-0 pointer-events-none'" x-ref="overlay"></span>
		</template>
		<?php
	}
}
