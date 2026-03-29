<?php

/**
 * ColonnaImmagini Block
 *
 * @autore Alessio Pangos
 */

namespace Classes\Blocks;

// Se questo file viene chiamato direttamente, termina l'esecuzione.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class ColonnaImmagini extends BaseBlock {



	public function __construct( $getField = false, $block = null ) {
		parent::__construct( $getField, $block );
	}

	public function render() {
		$this->setup();

		echo $this->container; ?>
		<div class="grid md:grid-cols-12 gap-gap">
			<?php
			if ( have_rows( 'colonna_immagini' ) ) :

				$index = 1; // Contatore per tracciare l'indice del ciclo

				while ( have_rows( 'colonna_immagini' ) ) :
					the_row();
					$linkArray = Utils::GetLinkAndTarget( 'link', false, get_sub_field( 'link_immagine' ) );
					// Determina il valore di data-gs-reveal in base all'indice
					$revealDirection = ( $index % 2 === 0 ) ? 'reveal_fromRight' : 'reveal_fromLeft';
					?>
					<div data-gs-reveal="<?php echo $revealDirection; ?>" class="md:col-span-6 relative group">
						<figure class="overflow-hidden">
							<?php

							Utils::LinkOpen( $linkArray, '', '' );

							new \Components\Image( 'immagine', ' w-full transform transition-transform duration-300 group-hover:scale-105', false, '', '' );
							?>

							<div class="absolute bottom-gap left-0 grid grid-cols-6 gap-gap w-full">
								<?php new \Components\Title( 'titolo', ' text-white col-start-2 col-span-5 !text-[14px]', false, '', '' ); ?>
							</div>

							<?php
							Utils::LinkClose( $linkArray );
							?>
						</figure>
					</div>
					<?php
					++$index; // Incrementa l'indice a ogni iterazione
				endwhile;
				?>
			<?php endif; ?>
		</div>

		<?php
		echo $this->containerClose;
	}
}
?>
