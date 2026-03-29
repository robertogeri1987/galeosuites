<?php

/**
 * CTA Block
 *
 * @author Alessio Pangos
 */

namespace Classes\Blocks;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class CTA extends BaseBlock {


	protected $text;
	protected $cta;
	protected $bg;
	protected $centerText;
	protected $whiteText;
	protected $textClass;

	public function __construct( $getField = false, $block = null ) {

		parent::__construct( $getField, $block );
		$this->bg         = parent::ACF( 'immagine_di_sfondo' );
		$this->whiteText  = parent::ACF( 'testo_bianco' );
		$this->centerText = parent::ACF( 'testo_centrato' );
		$this->textClass  = '';
	}

	public function render() {

		$this->setup();

		echo $this->container;

		if ( $this->whiteText ) {
			$this->textClass = ' text-white';
		}
		$containerClass = '';
		if ( $this->centerText ) {
			$containerClass = ' text-center';
		}

		?>
		<div class="cta-block w-full overflow-hidden relative min-h-screen md:min-h-[720px] flex items-center">
			<?php
			new \Components\Image( 'immagine_di_sfondo', 'w-full h-full absCenter object-cover' );
			?>

			<div class="inner-container mx-auto py-dgap <?php echo $containerClass; ?> gap-gap">
				<?php
				new \Components\Title( 'titolo', ' block relative z-1 uppercase' . $this->textClass, 'title-lg' );
				new \Components\Title( 'sottotitolo', ' block relative z-1' . $this->textClass, 'title-lg' );
				new \Components\Text( 'testo', ' block relative z-1 md:w-[600px] mx-auto md:max-w-full md:mt-gap' . $this->textClass );
				new \Components\LinkWithImage( 'cta', ' mt-gap block uppercase relative z-1 text-white group', true, "<span class='inline-block mr-gap'>" . __( 'Prenota ora', 'ap-wp-theme' ) . '</span> ' . ap_svg( 'arrow-button', null, 'rem:h-[74px] rem:w-[74px] fill-primary hover:fill-hover stroke-white inline transform transition-transform duration-300 group-hover:translate-x-2', true ) );

				?>
			</div>
		</div>
		<?php

		echo $this->containerClose;
	}
}
