<?php
/**
 * IconeCamere Block
 *
 * @author Alessio Pangos
 */
namespace Classes\Blocks;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class IconeCamere extends BaseBlock {

	public function __construct( $getField = false, $block = null ) {
		parent::__construct( $getField, $block );
	}

	public function render() {

		$this->setup();

		echo $this->container;

		new \Components\Title( 'titolo' );
		new \Components\Text('testo');

		if (have_rows('dettagli')):
			while (have_rows('dettagli')) : the_row();
				$icon = get_sub_field('icona');
				$text = get_sub_field('testo');
				?>
				<div class="archive-post__details flex items-center gap-2 text-sm mb-gap">
					<?php echo ap_svg($icon, '', 'w-[18px] h-[18px] fill-primary'); ?>
					<span><?php echo esc_html($text); ?></span>
				</div>
				<?php
			endwhile;
		endif;


		echo $this->containerClose;

	}

}
