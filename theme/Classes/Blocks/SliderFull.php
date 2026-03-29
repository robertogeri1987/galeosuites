<?php

/**
 * SliderFull Block
 *
 * @author Alessio Pangos
 */

namespace Classes\Blocks;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class SliderFull extends BaseBlock {

	public function __construct( $getField = false, $block = null ) {
		parent::__construct( $getField, $block );
	}

	public function render() {

		$this->setup();

		echo $this->container;

		if ( have_rows( 'slide' ) ) :

			?>
			<div class="acf-slider__container--carousel-outer-full relative grid grid-cols-1 md:grid-cols-12 gap-gap">
				<div class="md:col-span-12 text-center">
					<?php new \Components\Title( 'titolo_slider', ' uppercase', 'title-xl' ); ?>
					<?php new \Components\TextWithTag( 'sottotitolo_slider', '' ); ?>
				</div>
				<div class="acf-slider__container--carousel swiper-container overflow-x-clip md:col-span-12">
					<div class="swiper-wrapper">
						<?php
						while ( have_rows( 'slide' ) ) :
							the_row();
							$vertical = get_sub_field( 'verticale' );
							$class    = '!w-1/2';
							if ( $vertical ) {
								$class = '!w-1/4';
							}
							$image = get_sub_field( 'immagine' );
							?>
							<div class="swiper-slide h-auto flex flex-col <?php echo $class; ?>">
									<a href="<?php echo $image['url']; ?>"
										data-pswp-width="<?php echo isset( $image['width'] ) ? $image['width'] : ''; ?>"
										data-pswp-height="<?php echo isset( $image['height'] ) ? $image['height'] : ''; ?>"
										class="lightbox-trigger">
										<?php Utils::SimpleACFImg( $image, 'full', ' w-auto h-full object-cover mt-gap' ); ?>
									</a>
							</div>
							<?php
						endwhile;
						?>
					</div>
				</div>
				<div class="relative md:col-span-12 flex items-center justify-center gap-gap">
					<div class="acf-slider__arrow--left"><?php ap_svg( 'arrow-button', null, 'stroke-black fill-none w-[73px] h-[45px] rotate-180' ); ?></div>
					<div class="acf-slider__arrow--right"><?php ap_svg( 'arrow-button', null, 'stroke-black fill-none w-[73px] h-[45px]' ); ?></div>
				</div>
			</div>
			<?php
		endif;

		echo $this->containerClose;
	}
}
