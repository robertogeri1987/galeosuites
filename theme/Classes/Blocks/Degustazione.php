<?php

/**
 * Degustazione Block
 *
 * @author Alessio Pangos
 */

namespace Classes\Blocks;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Degustazione extends BaseBlock {

	public $showInfo = false;

	public function __construct( $getField = false, $block = null ) {
		parent::__construct( $getField, $block );
		$this->showInfo = parent::ACF( 'mostra_richiesta_informazioni' );
	}

	public function render() {

		$this->setup();

		$inverti = get_field( 'inverti' );

		$orderFirstColumn  = $inverti ? 'md:col-start-7 col-span-6' : 'md:col-start-1 col-span-6';
		$orderSecondColumn = $inverti ? 'md:col-start-1 col-span-5' : 'md:col-start-8 col-span-5';

		echo $this->container; ?>
		<div class="grid grid-cols-1 md:grid-cols-12 gap-gap">
			<div class="<?php echo $orderFirstColumn; ?> md:row-start-1">
				<?php
				new \Components\Image( 'immagine', 'w-full h-full object-cover', true, '', '' );
				?>
			</div>
			<div class="relative <?php echo $orderSecondColumn; ?> md:row-start-1 flex flex-col">

				<?php
				new \Components\Title( 'titolo', 'block uppercase' );
				new \Components\TextWithTag( 'sottotitolo', 'block' );
				new \Components\LinkWithImage( 'link', ' mt-gap', true, ap_svg( 'arrow-button', null, 'rem:h-[74px] rem:w-[74px] fill-primary hover:fill-hover stroke-current inline', true ) );
				new \Components\Text( 'testo', 'block relative mt-2gap' );
				?>


				<?php
				if ( have_rows( 'attributi' ) ) :
					?>
					<div class="mt-3gap">
						<?php
						while ( have_rows( 'attributi' ) ) :
							the_row();
							?>
							<div class="grid grid-cols-4 text-xs gap-gap mb-hgap">
								<div class="col-span-1 uppercase font-bold">
									<?php echo acf_esc_html( get_sub_field( 'chiave' ) ); ?>
								</div>
								<div class="col-span-2">
									<?php echo acf_esc_html( get_sub_field( 'valore' ) ); ?>
								</div>
							</div>

						<?php endwhile; ?>
					</div>
					<?php
				endif;

				if ( $this->showInfo ) :

					?>
					<div class="datepicker w-[420px] max-w-full mt-svgap md:pt-vgap md:mt-auto" data-datepicker data-lang="<?php echo ICL_LANGUAGE_CODE; ?>">
						<div class="relative w-full mb-gap">
							<div data-datepickerel></div>
						</div>
						<button disabled data-subject="<?php echo __( 'Richiesta informazioni per la degustazione in data: ', 'ap-wp-theme' ); ?>" class="base-button w-full uppercase" data-request-button><?php echo __( 'Richiedi informazioni', 'ap-wp-theme' ); ?></button>
					</div>
					<?php

				endif;

				?>

			</div>

		<?php
		echo $this->containerClose;
	}
}
