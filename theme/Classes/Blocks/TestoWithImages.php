<?php

/**
 * TestoWithImages Block
 *
 * @author Alessio Pangos
 */

namespace Classes\Blocks;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class TestoWithImages extends BaseBlock {


	public function __construct( $getField = false, $block = null ) {
		parent::__construct( $getField, $block );
	}

	public function render() {

		$this->setup();

		echo $this->container; ?>

		<div class="grid grid-cols-4 md:grid-cols-12 gap-gap mdd:alignfull mdd:overflow-x-clip">
			<div class="mdd:col-start-1 mdd:row-start-2 mdd:col-span-5 col-span-5 md:col-start-1 md:row-start-1 mdd:layout-padding">
				<?php
				new \Components\Title( 'titolo_testo_with_images', ' uppercase text-primary block relative z-[2]', 'title-lg' );
				new \Components\Text( 'descrizione', ' mt-gap mdd:max-w-[45%] md:max-w-[310px]' );
				// new \Components\LinkWithImage( 'cta', ' mt-gap block transform transition-transform duration-300 hover:translate-x-2', true );
				?>
				<div class="group">
					<?php
					new \Components\Button( 'cta', 'mt-4 uppercase mr-gap' );
					new \Components\LinkWithImage( 'cta', ' mt-2gap', true, ap_svg( 'arrow-button', null, 'rem:h-[74px] rem:w-[74px] fill-primary hover:fill-hover stroke-current inline transform transition-transform duration-300 group-hover:translate-x-2', true ) );
					?>
				</div>
			</div>
			<div class="mdd:col-start-1 mdd:col-span-3 md:col-span-3 md:col-start-3 md:row-start-2">
				<div class="relative mdd:pl-2gap" data-gs-reveal="reveal_fromLeft">
					<?php new \Components\Image( 'immagine_uno', 'w-full' ); ?>
				</div>
			</div>
			<div class="mdd:col-start-1 mdd:row-start-1 mdd:col-span-5 md:col-span-5 md:col-start-6 md:row-start-1">
				<div class="relative" data-gs-reveal="reveal_fromTop">
					<?php new \Components\Image( 'immagine_due', 'w-full', true, false, '', true, 'mdd:relative mdd:-left-gap' ); ?>
				</div>
			</div>
			<div class="mdd:col-start-3 mdd:row-start-2 mdd:col-span-3 md:col-span-4 md:col-start-9 md:row-start-1 md:translate-y-2/3">
				<div class="mdd:h-full mdd:block mdd:relative mdd:-left-gap mdd:w-[calc(100%+theme(spacing.2gap))]" data-gs-reveal="reveal_fromRight">
					<?php new \Components\Image( 'immagine_tre', ' w-full h-full mdd:object-cover mdd:object-left mdd:w-full', true, false, '', true, 'mdd:relative mdd:w-full mdd:-right-gap mdd:-top-svgap mdd:h-[calc(100%+theme(spacing.svgap))] mdd:w-[calc(100%+theme(spacing.2gap))] mdd:object-cover mdd:object-left ' ); ?>
				</div>
			</div>
		</div>

		<?php
		echo $this->containerClose;
	}
}
