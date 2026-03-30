<?php

/**
 * TitoloTestoImmagine Block
 *
 * @author Alessio Pangos
 */

namespace Classes\Blocks;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class TitoloTestoImmagine extends BaseBlock {


	public function __construct( $getField = false, $block = null ) {
		parent::__construct( $getField, $block );
	}

	public function render() {

		$this->setup();

		echo $this->container; ?>

		<div class="md:max-w-[920px] text-center mx-auto">
			<?php
			new \Components\Title( 'titolo', ' uppercase', 'title-4xl' );
			new \Components\Title( 'sottotitolo', 'mt-4gap' );
			new \Components\Text( 'testo', ' md:mt-gap' );
			new \Components\LinkWithImage( 'cta', ' mt-gap block transform transition-transform duration-300 hover:translate-x-2', true, ap_svg( 'arrow-button', null, 'rem:h-[74px] rem:w-[74px] fill-primary hover:fill-hover stroke-current inline', true ) );
			?>
		</div>
		<?php new \Components\Image( 'immagine', 'w-full mt-2gap' ); ?>
		<?php
		echo $this->containerClose;
	}
}
