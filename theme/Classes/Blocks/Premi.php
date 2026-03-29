<?php
/**
 * Premi Block
 *
 * @author Alessio Pangos
 */
namespace Classes\Blocks;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Premi extends BaseBlock {

	public function __construct( $getField = false, $block = null ) {
		parent::__construct( $getField, $block );
	}

	public function render() {

		$this->setup();

		echo $this->container;

		new \Components\Title( 'titolo', ' uppercase', 'title-xl' );

		if ( have_rows( 'premi_e_riconoscimenti' ) ) :

			?>
			<div x-data="{
				activeIndex: 0
			}" class="mt-2gap grid md:grid-cols-12 gap-x-gap">
				<div class="hidden border-b border-black md:col-span-12 md:grid grid-cols-12 gap-gap pb-hhgap">
					<div class="md:col-span-3 md:col-start-4">
						<span class="text-xs"><?php echo __( 'Premio', 'ap-wp-theme' ); ?></span>
					</div>
					<div class="md:col-span-2">
						<span class="text-xs"><?php echo __( 'Punteggio', 'ap-wp-theme' ); ?></span>
					</div>
					<div class="md:col-span-3">
						<span class="text-xs"><?php echo __( 'Prodotto / Azienda', 'ap-wp-theme' ); ?></span>
					</div>
					<div class="text-center">
						<span class="text-xs"><?php echo __( 'Anno', 'ap-wp-theme' ); ?></span>
					</div>
				</div>
				<div class="md:col-span-3 mdd:row-start-2 mdd:hidden">
					<div class="w-full sticky top-vgap left-0 pt-gap" x-ref="contentRef">
						<?php
						$counter = 0;
						while ( have_rows( 'premi_e_riconoscimenti' ) ) :
							the_row();
							?>
							<div x-show="activeIndex === <?php echo $counter; ?>">
								<?php
								Utils::SimpleACFImg( get_sub_field( 'immagine' ), 'full', ' w-full h-auto mb-gap' );
								new \Components\Title( 'titolo', '', 'title-md mb-hgap', false );
								new \Components\Text( 'testo', '', false );
								?>
							</div>
							<?php
							++$counter;
						endwhile;
						?>
					</div>
				</div>
				<div class="md:col-start-4 md:col-span-9 mdd:border-t mdd:border-black">
					<?php
					$counter = 0;
					while ( have_rows( 'premi_e_riconoscimenti' ) ) :
						the_row();
						$premio         = get_sub_field( 'premio' );
						$type           = get_sub_field( 'tipo_di_punteggio' );
						$punteggio      = get_sub_field( 'punteggio' );
						$punteggioFisso = get_sub_field( 'punteggio_fisso' );
						$prodotto       = get_sub_field( 'prodotto' );
						$anno           = get_sub_field( 'anno' );
						?>
						<div class="grid md:grid-cols-9 gap-gap border-b border-black py-gap group relative overflow-hidden mdd:grid mdd:grid-cols-2" @mouseenter="activeIndex = <?php echo $counter; ?>" @click="activeIndex = <?php echo $counter; ?>">
							<div class="md:col-span-3 block relative z-[2] group-hover:text-white transition-all duration-500 mdd:grid mdd:grid-cols-1 mdd:gap-hhgap">
								<span class="text-xs md:hidden"><?php echo __( 'Premio', 'ap-wp-theme' ); ?></span>
								<span class="text-left text-lg md:pl-hgap"><?php echo $premio; ?></span>
							</div>
							<div class="md:col-span-2 block relative z-[2] group-hover:text-white transition-all duration-500 mdd:grid mdd:grid-cols-1 mdd:gap-hhgap md:flex md:items-center">
								<span class="text-xs md:hidden"><?php echo __( 'Punteggio', 'ap-wp-theme' ); ?></span>
								<span class="text-lg flex items-center justify-start">
									<?php
									if ( $type === 'testo' ) :
										echo $punteggio;
										endif;
									if ( $type === 'stella' ) :
										for ( $i = 0; $i < $punteggioFisso; $i++ ) {
											ap_svg( 'stella', '', 'w-[18px] h-[18px] fill-black group-hover:fill-white transition-all duration-500 inline mr-[2px]' );
										}
									endif;
									if ( $type === 'foglia' ) :
										for ( $i = 0; $i < $punteggioFisso; $i++ ) {
											ap_svg( 'foglia', '', 'w-[16px] h-[30px] fill-black group-hover:fill-white transition-all duration-500' );
										}
									endif;
									?>
								</span>
							</div>
							<div class="md:col-span-3 block relative z-[2] group-hover:text-white transition-all duration-500 mdd:grid mdd:grid-cols-1 mdd:gap-hhgap">
								<span class="text-xs md:hidden"><?php echo __( 'Prodotto / Azienda', 'ap-wp-theme' ); ?></span>
								<span class="text-lg"><?php echo $prodotto; ?></span>
							</div>
							<div class="md:text-center block relative z-[2] group-hover:text-white transition-all duration-500 mdd:grid mdd:grid-cols-1 mdd:gap-hhgap">
								<span class="text-xs md:hidden"><?php echo __( 'Anno', 'ap-wp-theme' ); ?></span>
								<span class="text-lg"><?php echo $anno; ?></span>
							</div>
							<span class="md:col-span-9 opacity-0 w-full h-full bg-primary absolute top-0 left-0 group-hover:opacity-100 transition-all duration-500  block z-[1]"></span>
						</div>
						<?php
						++$counter;
					endwhile;
					?>
				</div>
			</div>
			<?php
		endif;

		echo $this->containerClose;
	}
}
