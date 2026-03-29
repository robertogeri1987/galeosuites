<?php

/**
 * TestoColonneImmagine Block
 *
 * @author Alessio Pangos
 */

namespace Classes\Blocks;

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

class TestoColonneImmagine extends BaseBlock
{

	public function __construct($getField = false, $block = null)
	{
		parent::__construct($getField, $block);
	}

	public function render()
	{

		$this->setup();

		echo $this->container; ?>

		<div class="grid md:grid-cols-12 gap-gap">
			<div class="md:col-span-6 md:pr-4gap">
				<?php
				new \Components\TextWithTag('sottotitolo', '');
				new \Components\Text('testo_prima_colonna', ' text-xl leading-[40px] md:text-2xl md:leading-[48px]');
				?>
			</div>
			<div class="md:col-span-6">
				<?php
				new \Components\Image('immagine', ' w-full');
				new \Components\Text('testo_seconda_colonna', ' mdd:mt-2gap md:mt-4gap');
				?>
			</div>
		</div>

<?php echo $this->containerClose;
	}
}
