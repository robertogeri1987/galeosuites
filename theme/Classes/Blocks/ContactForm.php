<?php

/**
 * ContactForm Block
 *
 * @author Alessio Pangos
 */

namespace Classes\Blocks;

// If this file is called directly, abort.
if (! defined('WPINC')) {
	die;
}

class ContactForm extends BaseBlock
{

	protected $container;
	protected $containerClose;
	protected $shortcode;

	public function __construct($getField = false, $block = null)
	{
		parent::__construct($getField, $block);
		$this->shortcode = parent::ACF('shortcode_modulo_di_contatto') ? parent::ACF('shortcode_modulo_di_contatto') : get_field('shortcode_modulo_di_contatto_generico', 'options');
	}

	public function render()
	{

		$this->setup();

		echo $this->container;

?>
		<div class="grid grid-cols-1 md:grid-cols-12 gap-gap relative z-[1]">
			<section class="md:col-span-5 block relative z-[2]">
				<div class="flex items-center gap-gap">
					<?php new \Components\Title('titolo_modulo', ' block relative z-[2]', 'title-lg');
					echo ap_svg('arrow-button', null, 'rem:h-[74px] rem:w-[74px] fill-primary translate-y-[5px] hover:fill-hover stroke-black inline transform transition-transform duration-300 group-hover:translate-x-2', true);
					?>
				</div>
				<?php new \Components\Title('sottotitolo_modulo', ' mt-hgap block relative z-[2]', 'title-sm'); ?>
				<?php Utils::ProseText(parent::ACF('testo_modulo'), ' mt-hgap block relative z-[2]'); ?>

				<div class="pt-gap md:pt-vgap mdd:mb-vgap">
					<p>
					<p><?php _e('Seguici su:', 'ap-wp-theme-tw'); ?></p>
					</p>
					<div class="md:col-span-3 flex items-start justify-start gap-gap pt-hgap">
						<a href="<?php echo get_field('facebook_link', 'options'); ?>" rel="noopener" target="_blank"><?php ap_svg('facebook', 'Facebook', 'w-[18px] h-[18px] fill-black hover:fill-primary transition-all duration-300'); ?></a>
						<a href="<?php echo get_field('instagram_link', 'options'); ?>" rel="noopener" target="_blank"><?php ap_svg('instagram', 'instagram', 'w-[18px] h-[18px] fill-black hover:fill-primary transition-all duration-300'); ?></a>
					</div>
				</div>
			</section>
			<section class="md:col-start-7 md:col-span-6">
				<?php
				echo do_shortcode($this->shortcode);
				?>
				<?php Utils::ProseText(parent::ACF('testo_a_destra'), ' mt-2gap'); ?>
			</section>
		</div>
<?php

		echo $this->containerClose;
	}
}
