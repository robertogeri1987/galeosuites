<?php

/**
 * Location Block
 *
 * @author Alessio Pangos
 */

namespace Classes\Blocks;

// If this file is called directly, abort.
if (! defined('WPINC')) {
	die;
}

class Location extends BaseBlock
{

	public function __construct($getField = false, $block = null)
	{
		parent::__construct($getField, $block);
	}

	public function render()
	{

		$this->setup();


		$inverti = get_field('inverti');


		$orderFirstColumn = $inverti ? 'md:order-3' : 'md:order-1';
		$orderSecondColumn = $inverti ? 'md:order-2' : 'md:order-2';
		$orderThirdColumn = $inverti ? 'md:order-1' : 'md:order-3';

		echo $this->container; ?>
		<div class="grid grid-cols-12 gap-gap">
			<div class="col-span-12 md:col-span-4 relative <?php echo $orderFirstColumn; ?>">

				<?php
				new \Components\Title('titolo', 'block uppercase');
				new \Components\TextWithTag('sottotitolo', 'block');
				new \Components\LinkWithImage('link', ' block relative z-1 uppercase group', true, "<span class='inline-block mr-gap'>" . __('Scopri di più', 'ap-wp-theme') . "</span> " . ap_svg('arrow-button', null, 'rem:h-[74px] rem:w-[74px] fill-primary hover:fill-hover stroke-current inline transform transition-transform duration-300 group-hover:translate-x-2', true));
				new \Components\Text('testo', 'block relative mt-hgap');
				?>

				<div class="md:absolute mdd:mt-gap bottom-0 w-full">

					<?php new \Components\LinkWithImage('dove_siamo', ' mt-gap block relative z-1 button mb-2gap relative base-button--outline group', true, "<span class='inline-block'>" . __('Dove siamo', 'ap-wp-theme') . "</span> " . ap_svg('arrow-right-carousel-alt', null, 'rem:h-[24px] rem:w-[24px] fill-primary hover:fill-hover stroke-current absolute absCenter !left-auto right-0 inline transform rotate-45 transition-transform duration-300 group-hover:rotate-90', true)); ?>

					<?php


					if (have_rows('attributi')): ?>
						<div class="grid grid-cols-2 gap-hgap">
							<?php while (have_rows('attributi')): the_row();
							?>
								<div class="grid grid-cols-2 text-xs">
									<div class="col-span-1 uppercase font-bold">
										<?php echo acf_esc_html(get_sub_field('chiave')); ?>
									</div>
									<div class="col-span-1">
										<?php echo acf_esc_html(get_sub_field('valore')); ?>
									</div>
								</div>

							<?php endwhile; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<div class="col-span-8 md:col-span-5 <?php echo $orderSecondColumn; ?>">
				<?php
				new \Components\Image('immagine', 'w-full h-full object-cover', true, '', '');
				?>
			</div>
			<div class="col-span-4 md:col-span-3 <?php echo $orderThirdColumn; ?>">
				<?php
				new \Components\Image('immagine_due', 'w-full h-full object-cover', true, '', '');
				?>
			</div>
		</div>

<?php echo $this->containerClose;
	}
}
