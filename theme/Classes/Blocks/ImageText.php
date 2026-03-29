<?php

/**
 * Simple Wysiwig Text Block
 *
 * @author Alessio Pangos
 */

namespace Classes\Blocks;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class ImageText extends BaseBlock {


	protected $invert;

	public function __construct( $getField = false, $block = null ) {

		parent::__construct( $getField, $block );
		$this->invert = parent::ACF( 'inverti' );
	}

	public function render() {

		$this->setup();

		echo $this->container;

		$classText = ' class="flex flex-col items-start justify-end md:col-span-4"';
		$classImg  = ' mdd:row-start-2 md:col-span-5 md:col-start-2';
		$mauto     = ' md:ml-auto';
		$reveal    = 'data-gs-reveal="reveal_fromLeft" ';
		$textRight = ' ';

		if ( $this->invert ) {
			$classText = ' class="flex flex-col items-start justify-end col-start-1 row-start-1 md:col-span-4 md:col-start-4 text-right"';
			$classImg  = ' md:col-start-2 mdd:row-start-2 md:row-start-1 md:col-span-4 md:col-start-8';
			$mauto     = ' md:mr-auto';
			$reveal    = 'data-gs-reveal="reveal_fromRight" ';
			$textRight = ' ml-auto';
		}

		?>
		<div class="grid grid-cols-1 md:grid-cols-12 gap-gap">
			<figure <?php echo $reveal; ?>class="w-full<?php echo $classImg; ?>">
				<?php echo Utils::SimpleACFImg( parent::ACF( 'immagine_img_txt' ), 'full', 'w-full' . $mauto ); ?>
			</figure>
			<section <?php echo $classText; ?>>
				<?php
				new \Components\Title( 'intestazione_img_txt', 'block uppercase ' . $textRight, 'title-lg' );
				new \Components\TextWithTag( 'contesto_intestazione_img_txt', ' block' . $textRight );
				new \Components\LinkWithImage( 'cta', ' mt-gap block transform transition-transform duration-300 hover:translate-x-2' . $textRight, true, ap_svg( 'arrow-button', null, 'rem:h-[74px] rem:w-[74px] fill-primary hover:fill-hover stroke-current inline', true ) );
				new \Components\Text( 'testo_img_txt', ' md:max-w-[320px]' . $textRight);
				?>
			</section>
		</div>
		<?php

		echo $this->containerClose;
	}
}
