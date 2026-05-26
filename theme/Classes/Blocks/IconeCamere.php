<?php

/**
 * IconeCamere Block
 *
 * @author Alessio Pangos
 */

namespace Classes\Blocks;

// If this file is called directly, abort.
if (! defined('WPINC')) {
	die;
}

class IconeCamere extends BaseBlock
{

	public function __construct($getField = false, $block = null)
	{
		parent::__construct($getField, $block);
	}

	public function render()
	{

		$this->setup();

		echo $this->container;

		new \Components\Title('titolo', 'title-5xl');
		new \Components\Text('testo', 'text-md mt-2gap'); ?>

		<div class="grid grid-cols-3 gap-[10px] mt-[50px] max-w-[800px]">
			<?php
			if (have_rows('dettagli')):
				while (have_rows('dettagli')) : the_row();
					$icon = get_sub_field('icona');
					$text = get_sub_field('testo');
			?>
					<div class="archive-post__details flex items-center gap-2 text-sm mb-gap">
						<?php echo ap_svg($icon, '', 'w-[20px] h-[20px] fill-primary'); ?>
						<span class="text-[14px]"><?php echo esc_html($text); ?></span>
					</div>
			<?php
				endwhile;
			endif;
			?>
		</div>
		<div>
			<a class="base-button base-button--invert mt-gap"
				href="/contatti/">
				<?php esc_html_e('Richiedi', 'ap-wp-theme'); ?>
			</a>
			<a class="base-button mt-gap"
				href="/contatti/">
				<?php esc_html_e('Prenota', 'ap-wp-theme'); ?>
			</a>
		</div>

<?php echo $this->containerClose;
	}
}
