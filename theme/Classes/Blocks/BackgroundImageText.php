<?php

/**
 * BackgroundImageText Block
 *
 * @author Alessio Pangos
 */

namespace Classes\Blocks;

// Se questo file viene chiamato direttamente, termina l'esecuzione.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class BackgroundImageText extends BaseBlock {



	public function __construct( $getField = false, $block = null ) {
		parent::__construct( $getField, $block );
	}

	public function render() {
		$this->setup();

		echo $this->container;

		// Ottieni l'URL dell'immagine di sfondo
		$backgroundImage = parent::ACF( 'immagine_sfondo' );
		$backgroundStyle = $backgroundImage ? 'style="background-image: url(' . esc_url( $backgroundImage['url'] ) . ');"' : '';
		?>
		<div class="relative bg-cover bg-center mdd:min-h-screen mdd:py-2gap md:min-h-[720px] flex items-center justify-center" <?php echo $backgroundStyle; ?>>
			<span class="absCenter w-screen h-full bg-black/40 z-2"></span>
			<div class="grid md:grid-cols-12 gap-gap layout-container mx-auto z-10">
				<div class="col-span-12 md:col-start-2 md:col-span-10 text-center text-white">
					<?php
					new \Components\Title( 'titolo_bg_img_txt', ' uppercase', 'title-lg' );
					new \Components\TextWithTag( 'testo_bg_img_txt', '' );
					new \Components\Text( 'testo_bg_img', ' text-white mt-2gap md:columns-2' );
					?>
					<div class="group">
						<?php
						new \Components\Button( 'cta_bg_img_txt', 'mt-4 uppercase mr-gap' );
						new \Components\LinkWithImage( 'cta_bg_img_txt', ' mt-2gap', true, ap_svg( 'arrow-button', null, 'rem:h-[74px] rem:w-[74px] fill-primary hover:fill-hover stroke-current inline transform transition-transform duration-300 group-hover:translate-x-2', true ) );
						?>
					</div>
				</div>
			</div>
		</div>
		<?php

		echo $this->containerClose;
	}
}
?>
