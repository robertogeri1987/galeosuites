<?php

/**
 * PersonSlider Block
 *
 * @author Alessio Pangos
 */

namespace Classes\Blocks;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class PersonSlider extends BaseBlock {

	public function __construct( $getField = false, $block = null ) {
		parent::__construct( $getField, $block );
	}

	public function render() {

		$this->setup();

		echo $this->container;

		if ( have_rows( 'slide' ) ) :

			?>
			<div class="acf-slider__container--carousel-outer-person relative grid grid-cols-1 md:grid-cols-12 gap-gap">
				<div class="md:col-span-12 text-center">
					<?php new \Components\Title( 'titolo_slider', ' uppercase', 'title-xl' ); ?>
					<?php new \Components\TextWithTag( 'sottotitolo_slider', '' ); ?>
				</div>
				<div class="acf-slider__container--carousel swiper-container overflow-x-clip md:col-span-12 person-slider">
					<div class="swiper-wrapper">
						<?php
						while ( have_rows( 'slide' ) ) :
							the_row();
							?>
							<div class="swiper-slide h-auto">
								<div class="flex flex-col h-full">
									<?php Utils::SimpleACFImg( get_sub_field( 'immagine' ), 'full', ' w-full h-auto mt-gap' ); ?>
									<?php new \Components\Title( 'titolo', ' ', 'title-md mb-gap mt-2gap', false ); ?>
									<?php new \Components\Text( 'testo', ' mb-auto', false ); ?>
								</div>
							</div>
							<?php
						endwhile;
						?>
					</div>
				</div>
			</div>
			<?php
		endif;

		echo $this->containerClose;
	}
}
