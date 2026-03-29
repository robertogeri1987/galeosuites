<?php

/**
 * MediaTextSlider Block
 *
 * @author Alessio Pangos
 */

namespace Classes\Blocks;

// If this file is called directly, abort.
if (! defined('WPINC')) {
	die;
}

class MediaTextSlider extends BaseBlock
{

	public function __construct($getField = false, $block = null)
	{
		parent::__construct($getField, $block);
	}

	public function render()
	{

		$this->setup();

		echo $this->container;

		if (have_rows('slide')) :

?>
			<div class="acf-slider__container--carousel-outer-labels relative">
				<div class="swiper-container overflow-hidden">
					<div class="swiper-wrapper">
						<?php
						while (have_rows('slide')) :
							the_row();
							$colr  = get_sub_field('colore_sfondo_testo');
							$style = '';
							if ($colr) {
								$style = ' style="background-color: ' . $colr . '"';
							}
						?>
							<div class="swiper-slide">
								<div class="grid md:grid-cols-2">
									<div class="text-white text-center flex flex-col items-center justify-center bg-secondary mdd:px-2gap p-[100px] lg:p-[180px] md:min-h-[600px]" <?php echo $style; ?>>
										<?php new \Components\Title('titolo', ' uppercase', 'title-xl', false); ?>
										<?php new \Components\TextWithTag('sottotitolo', ' title-xl mb-gap', false); ?>
										<?php new \Components\Text('testo', '-invert', false); ?>
									</div>
									<div>
										<?php if (get_sub_field('tipo_media') === 'immagine') : ?>
											<?php new \Components\Image('immagine', 'w-full h-full object-cover', false, false, '', true, 'w-full h-full object-cover'); ?>
										<?php else : ?>
											<video class="w-full h-full object-cover" playsinline autoplay loop muted>
												<source src="<?php echo get_sub_field('url_video'); ?>" type="video/mp4">
											</video>
										<?php endif; ?>
									</div>
								</div>
							</div>
						<?php
						endwhile;
						?>
					</div>
					<div class="mdd:mt-gap md:absolute grid md:grid-cols-2 w-full h-4gap md:bottom-[40px] md:left-0 z-[2]">
						<div class="relative">
							<div class="absCenter flex items-center justify-center gap-gap text-black md:text-white">
								<div class="acf-slider__arrow--left"><?php ap_svg('arrow-left-carousel-alt', null, 'stroke-black md:stroke-white w-[33px] h-[25px] fill-none'); ?></div>
								<span class="swiper-current">01</span> <span>-</span> <?php echo (count(get_field('slide')) < 10 ? '0' : '') . count(get_field('slide')); ?>
								<div class="acf-slider__arrow--right"><?php ap_svg('arrow-right-carousel-alt', null, 'stroke-black md:stroke-white w-[33px] h-[25px] fill-none'); ?></div>
							</div>
						</div>
						<div>

						</div>
					</div>
				</div>

			</div>
<?php

		endif;

		echo $this->containerClose;
	}
}
