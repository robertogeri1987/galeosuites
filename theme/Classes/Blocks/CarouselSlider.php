<?php
/**
 * CarouselSlider Block
 *
 * @author Alessio Pangos
 */
namespace Classes\Blocks;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class CarouselSlider extends BaseBlock {

	public function __construct( $getField = false, $block = null ) {
		parent::__construct( $getField, $block );
	}

	public function render() {

		$this->setup();

		echo $this->container;

		if ( have_rows( 'slide' ) ) :

			?>
			<div class="acf-slider__container--carousel-outer relative grid grid-cols-1 md:grid-cols-12 gap-gap">
				<div class="md:col-span-3">
					<?php new \Components\Title( 'titolo_slider', ' uppercase', 'title-xl' ); ?>
				</div>
				<div class="acf-slider__container--carousel swiper-container overflow-x-clip md:col-span-9 person-slider" data-loop="true">
					<div class="swiper-wrapper">
						<?php
						while ( have_rows( 'slide' ) ) :
							the_row();
							?>
							<div class="swiper-slide h-auto">
								<div class="flex flex-col h-full">
									<?php new \Components\Title( 'titolo', ' ', 'title-md mb-gap', false ); ?>
									<?php new \Components\Text( 'testo', ' mb-auto', false ); ?>
									<?php Utils::SimpleACFImg( get_sub_field( 'immagine' ), 'full', ' w-full h-auto mt-gap' ); ?>
								</div>
							</div>
							<?php
						endwhile;
						?>
					</div>
				</div>
				<div class="relative md:col-span-12 flex items-center justify-end gap-gap">
					<div class="acf-slider__arrow--left"><?php ap_svg( 'arrow-left-carousel-alt', null, 'stroke-black fill-none w-[33px] h-[25px]' ); ?></div>
					<div class="acf-slider__arrow--right"><?php ap_svg( 'arrow-right-carousel-alt', null, 'stroke-black fill-none w-[33px] h-[25px]' ); ?></div>
				</div>
			</div>
			<?php
		endif;

		echo $this->containerClose;
	}
}
