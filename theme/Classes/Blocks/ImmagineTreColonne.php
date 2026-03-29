<?php

/**
 * ImmagineTreColonne Block
 *
 * @author Alessio Pangos
 */

namespace Classes\Blocks;

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

class ImmagineTreColonne extends BaseBlock
{

	public function __construct($getField = false, $block = null)

	{
		parent::__construct($getField, $block);
	}

	public function render()
	{

		$this->setup();
		echo $this->container;

?>

		<div class="grid md:grid-cols-12 gap-gap layout-container mx-auto">

			<?php if (have_rows('immagini')): ?>

				<?php while (have_rows('immagini')): the_row();
				?>
					<div class="md:col-span-4">
						<?php new \Components\Image('immagine', 'w-full', false); ?>
					</div>
				<?php endwhile; ?>

			<?php endif; ?>
		</div>

<?php echo $this->containerClose;
	}
}
