<?php

/**
 * LinkTreColonne Block
 *
 * @author Alessio Pangos
 */

namespace Classes\Blocks;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class LinkTreColonne extends BaseBlock {



	public function __construct( $getField = false, $block = null ) {
		parent::__construct( $getField, $block );
	}

	public function render() {

		$this->setup();

		echo $this->container; ?>


		<?php

		$mostra_intestazione = get_field( 'colonna_intestazione' );

		if ( $mostra_intestazione ) :
			?>
			<div class="grid grid-cols-1 md:grid-cols-12 gap-hgap mb-hgap text-white">
				<div class="col-span-12 bg-cover bg-center p-4 text-center flex flex-col items-center justify-center relative min-h-[650px] overflow-hidden">
					<div class="md:grid md:grid-cols-12 gap-gap">
						<div class="md:col-span-8 md:col-start-3">
							<?php
							new \Components\Image( 'immagine_colonna_intestazione', 'w-full h-full absCenter object-cover -z-1', true, '', '' );
							new \Components\Title( 'titolo_intestazione', 'block uppercase relative z-1' );
							new \Components\TextWithTag( 'sottotitolo_intestazione', 'block relative z-1' );
							new \Components\Text( 'testo_intestazione', ' block relative z-1 md:columns-2 mt-gap' );

							?>
							<div class="group">
								<?php
								new \Components\LinkWithImage( 'cta', ' mt-gap block uppercase relative z-1 text-white group', true, ap_svg( 'arrow-button', null, 'rem:h-[74px] rem:w-[74px] fill-primary hover:fill-hover stroke-white inline transform transition-transform duration-300 group-hover:translate-x-2', true ) );
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( have_rows( 'colonna' ) ) : ?>
			<div class="grid grid-cols-1 md:grid-cols-12 gap-hgap text-white">
				<?php
				while ( have_rows( 'colonna' ) ) :
					the_row();
					?>
					<?php

					$linkArray = Utils::GetLinkAndTarget( 'link', false, get_sub_field( 'link_colonna' ) );
					?>
					<div class="col-span-1 group md:col-span-4 flex justify-center items-center text-center relative min-h-[600px] bg-primary overflow-hidden">
						<?php
						Utils::LinkOpen( $linkArray, '', '' );
						?>
						<figure>
							<?php
							new \Components\Image( 'immagine', ' w-full h-full absCenter object-cover z-0', false, '', '' );
							?>
						</figure>
						<?php
						if ( ! get_sub_field( 'testo_si' ) ) :
							echo '<div class="absolute left-0 top-0 h-full w-full bg-black/40 transition duration-300 ease-in-out group-hover:bg-black/0"></div>';
						endif;
						?>
						<div class="relative z-2">
							<?php new \Components\Title( 'titolo', ' uppercase', 'title-lg', false ); ?>
						</div>

						<?php
						if ( get_sub_field( 'testo_si' ) ) :
							?>
							<div class="absolute w-full bottom-0 flex flex-col items-center justify-center gap-hgap p-gap">
								<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/arrow-down.svg" alt="Arrow Down" class="h-[166px] w-auto transform transition-transform duration-300 group-hover:translate-y-2">
								<?php new \Components\Text( 'testo', ' text-white', false ); ?>
							</div>
							<?php
						endif;
						?>

						<?php
						Utils::LinkClose( $linkArray );
						?>

					</div>
				<?php endwhile; ?>
			</div>
		<?php endif; ?>


		<?php
		echo $this->containerClose;
	}
}

?>
