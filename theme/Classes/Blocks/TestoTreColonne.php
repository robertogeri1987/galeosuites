<?php

/**
 * TestoTreColonne Block
 *
 * @author Alessio Pangos
 */

namespace Classes\Blocks;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class TestoTreColonne extends BaseBlock {

	public $simmetrico;
	public $secondaColonnaImmagine;
	public $pulsante;
	public $pulsantePrenota;
	public $griglia;
	public $grigliaAggiuntiva;

	public function __construct( $getField = false, $block = null ) {

		parent::__construct( $getField, $block );
		$this->simmetrico             = parent::ACF( 'simmetriche' );
		$this->secondaColonnaImmagine = parent::ACF( 'tipo_immagine_seconda_colonna' );
		$this->pulsante               = parent::ACF( 'pulsante' );
		$this->pulsantePrenota        = parent::ACF( 'pulsante_prenota' );
		$this->griglia                = parent::ACF( 'terza_colonna_griglia' );
		$this->grigliaAggiuntiva      = parent::ACF( 'griglia_aggiuntiva' );
	}

	public function render() {

		$this->setup();

		echo $this->container;

		$classSimmetriche        = '';
		$classContenitoreGriglia = '';
		$classGriglia            = '';

		if ( $this->simmetrico ) {
			$classSimmetriche        = 'md:col-start-5';
			$classContenitoreGriglia = 'flex items-end';
			$classGriglia            = ' grid grid-cols-4 gap-hgap';
		}
		if ( $this->grigliaAggiuntiva ) {
			$classContenitoreGriglia = 'flex flex-col justify-between';
		}

		?>

		<div class="grid md:grid-cols-12 gap-gap">
			<div class="md:col-span-3">
				<?php
				new \Components\TextWithTag( 'sottotitolo', ' uppercase' );
				new \Components\Title( 'titolo', ' uppercase', 'title-lg' );
				if ( $this->pulsante ) {
					new \Components\LinkWithImage( 'cta', ' mt-gap block relative z-1 button mb-hgap relative base-button--outline group', true, "<span class='inline-block'>" . __( 'Dove siamo', 'ap-wp-theme' ) . '</span> ' . ap_svg( 'arrow-right-carousel-alt', null, 'rem:h-[24px] rem:w-[24px] fill-primary hover:fill-hover stroke-current absolute absCenter !left-auto right-0 inline transform rotate-45 transition-transform duration-300 group-hover:rotate-90', true ) );
				}
				if ( $this->pulsantePrenota ) {
					new \Components\Link( 'cta_prenota', ' base-button w-full text-center', );
				}
				?>
			</div>
			<div class="md:col-span-4 <?php echo $classSimmetriche; ?>">
				<?php
				if ( $this->secondaColonnaImmagine ) {
					new \Components\Image( 'immagine_seconda_colonna', 'w-full' );
				} else {
					new \Components\Text( 'testo_seconda_colonna', '' );
				}
				?>
			</div>
			<div class="md:col-span-4 relative text-[12px] <?php echo $classContenitoreGriglia; ?>">
				<?php
				if ( $this->griglia ) {
					if ( have_rows( 'griglia' ) ) :
						?>
						<div class="<?php echo $classGriglia; ?>">
							<?php
							while ( have_rows( 'griglia' ) ) :
								the_row();
								?>
								<div class="col-span-1 md:col-start-2 uppercase">
									<?php echo acf_esc_html( get_sub_field( 'chiave' ) ); ?>
								</div>
								<div class="col-span-1 md:col-start-3">
									<?php echo acf_esc_html( get_sub_field( 'valore' ) ); ?>
								</div>

							<?php endwhile; ?>
						</div>
						<?php
						endif;
				} else {
					new \Components\Text( 'testo_terza_colonna', '' );
					if ( $this->grigliaAggiuntiva ) {
						if ( have_rows( 'griglia_aggiuntiva' ) ) :
							?>
							<div class="<?php echo $classGriglia; ?> mt-2gap">
								<?php
								while ( have_rows( 'griglia_aggiuntiva' ) ) :
									the_row();
									?>
									<div class="col-span-1 uppercase font-bold">
										<?php echo acf_esc_html( get_sub_field( 'chiave' ) ); ?>
									</div>
									<div class="col-span-1">
										<?php echo acf_esc_html( get_sub_field( 'valore' ) ); ?>
									</div>
								<?php endwhile; ?>
							</div>
						<?php endif; ?>
						<?php
					}
				}
				?>
			</div>
		</div>

		<?php
		echo $this->containerClose;
	}
}
