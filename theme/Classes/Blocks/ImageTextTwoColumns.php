<?php

/**
 * Simple Wysiwig Text Block
 *
 * @author Alessio Pangos
 */

namespace Classes\Blocks;

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

class ImageTextTwoColumns extends BaseBlock
{

	protected $invert;

	public function __construct($getField = false, $block = null)
	{

		parent::__construct($getField, $block);
		$this->invert = parent::ACF('inverti');
	}

	public function render()
	{

		$this->setup();

		echo $this->container;

		$classText = ' class="flex flex-col items-start md:col-span-6"';
		$classImg  = ' mdd:row-start-2 md:col-span-6 md:col-start-1';
		$reveal    = 'data-gs-reveal="reveal_fromLeft" ';
		$textRight = ' ';


		if ($this->invert) {
			$classText = ' class="flex flex-col items-start col-start-1 row-start-1 md:col-span-6 md:col-start-1 text-right"';
			$classImg  = ' col-start-2 mdd:row-start-2 md:row-start-1 md:col-span-6 md:col-start-7';
			$reveal    = 'data-gs-reveal="reveal_fromRight" ';
			$textRight = ' ml-auto';
		}

?>
		<div class="grid grid-cols-1 md:grid-cols-12 gap-gap layout-container mx-auto">
			<figure <?php echo $reveal; ?> class="w-full<?php echo $classImg; ?>">
				<?php echo Utils::SimpleACFImg(parent::ACF('immagine_img_txt'), 'full', 'w-full'); ?>
			</figure>
			<section <?php echo $classText; ?>>
				<?php
				new \Components\TextWithTag('titolino', ' block uppercase text-xs' . $textRight);
				new \Components\Text('testo', ' md:text-[34px] md:leading-[50px]');
				?>
			</section>
		</div>
<?php

		echo $this->containerClose;
	}
}
