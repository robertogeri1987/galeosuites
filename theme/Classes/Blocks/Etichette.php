<?php
/**
 * Etichette Block
 *
 * @author Alessio Pangos
 */
namespace Classes\Blocks;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Etichette extends BaseBlock {

	public function __construct( $getField = false, $block = null ) {
		parent::__construct( $getField, $block );
	}

	public function render() {

		$this->setup();

		echo $this->container;

		if ( have_rows( 'etichetta' ) ) :

			$counter  = 1;
			$totalNum = count( get_field( 'etichetta' ) );
			?>
			<div class="grid grid-cols-2 md:grid-cols-5">
				<?php
				while ( have_rows( 'etichetta' ) ) :
					the_row();

					if ( $counter === 1 ) {
						?>
						<div class="col-span-2 md:col-span-5 grid grid-cols-1 md:grid-cols-2 relative">
							<?php new \Components\Image( 'immagine_di_sfondo', 'absCenter w-full h-full object-cover z-[0]', true, '', '', true, 'absCenter w-full h-full object-cover z-[0]' ); ?>
							<div class="mdd:flex mdd:items-center mdd:justify-center mdd:p-gap aspect-square relative block z-[1]" style="background: radial-gradient(circle at center, transparent 60%, <?php the_sub_field( 'colore_di_sfondo' ); ?> 60%);">
								<div class="md:absCenter block z-[2] !text-white text-center w-full max-w-[80%]">
									<?php the_sub_field( 'testo' ); ?>
								</div>
							</div>
							<div class="aspect-square relative block z-[1] p-3gap">
								<?php Utils::SimpleACFImg( get_sub_field( 'immagine' ), 'full', ' w-[90%] h-full absCenter object-contain' ); ?>
							</div>
						</div>
						<?php
					} else {
						$class = '';
						if ( $totalNum > 11 && $counter > 10 ) {
							$class = ' swiper-slide';
						}
						if ( $totalNum > 11 && $counter === 11 ) {
							?>
							<div class="acf-slider__container--carousel-outer-labels relative">
								<div class="swiper-container overflow-hidden">
									<div class="swiper-wrapper">
								<?php
						}
						?>
									<div class="flex group items-center justify-center py-3gap px-gap overflow-hidden relative<?php echo $class; ?>" style="background: <?php the_sub_field( 'colore_di_sfondo' ); ?>">
										<?php Utils::SimpleACFImg( get_sub_field( 'immagine' ), 'full', 'w-full h-auto mx-auto' ); ?>
										<div class="absolute top-full left-0 w-full h-full block z-[2] group-hover:top-0 transition-all duration-500" style="background: <?php the_sub_field( 'colore_di_sfondo' ); ?>">
											<div class="mdd:text-[9px] absCenter block z-[2] !text-white text-center w-full max-w-[90%]">
												<?php the_sub_field( 'testo' ); ?>
											</div>
										</div>
									</div>
									<?php
									if ( $totalNum > 11 && $counter === $totalNum ) {
										?>
									</div>
								</div>
								<div class="absolute top-full right-0 flex items-center justify-center gap-gap pt-hgap mx-auto md:w-full">
									<div class="acf-slider__arrow--left"><?php ap_svg( 'arrow-left-carousel-alt', null, 'stroke-black fill-none w-[33px] h-[25px]' ); ?></div>
									<div class="acf-slider__arrow--right"><?php ap_svg( 'arrow-right-carousel-alt', null, 'stroke-black fill-none w-[33px] h-[25px]' ); ?></div>
								</div>
							</div>
										<?php
									}
					}

					++$counter;
				endwhile;
				?>
			</div>
			<?php

		endif;

		echo $this->containerClose;
	}
}
