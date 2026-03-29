<?php
/**
 * BloccoSchede Block
 *
 * @author Alessio Pangos
 */
namespace Classes\Blocks;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class BloccoSchede extends BaseBlock {

	public function __construct( $getField = false, $block = null ) {
		parent::__construct( $getField, $block );
	}

	public function render() {

		$this->setup();

		echo $this->container;

		if ( have_rows( 'scheda' ) ) :

			?>
			<section x-data="{
					active: 0,
					fullWidth: 0,
					numSlides: <?php echo count( get_field( 'scheda' ) ); ?>,
					entries: $root.querySelectorAll('[data-entry]'),
					setActive(num) {
						this.active = num;
						if (window.Alpine.store('header').navigationMode === 'desktop') {
							this.entries[num].style.width = this.fullWidth + 'px';
							for (let i = 0; i < this.entries.length; i++) {
								if (i != num) {
									this.entries[i].style.width = 111 + 'px';
								}
							}
						}
					},
					resetSize() {
						if (window.Alpine.store('header').navigationMode === 'desktop') {
							this.fullWidth = this.$el.getBoundingClientRect().width - ((this.numSlides -1) * 111);
							this.entries[0].style.width = this.fullWidth + 'px';
							for (const entry of this.entries) {
								entry.querySelector('[data-content]').style.width = this.fullWidth + 'px';
							}
						} else {
							this.fullWidth = this.$el.getBoundingClientRect().width = '100%';
							for (const entry of this.entries) {
								entry.querySelector('[data-content]').style.width = '100%';
							}
						}
					},
					init() {
						this.resetSize();
					}
				}" class="w-full md:h-[60vh] min-h-[600px] relative mt-svgap text-white flex flex-col md:flex-row" x-resize.window.throttle="resetSize">
				<template x-if="$store.header.navigationMode === 'mobile'">
					<div class="flex flex-wrap">
						<?php
						$counter = 0;
						while ( have_rows( 'scheda' ) ) :
							the_row();
							$anno   = get_sub_field( 'anno' );
							$colore = get_sub_field( 'colore_etichetta' );
							?>
							<div class="text-white text-center px-hgap py-hhgap flex items-center justify-center flex-1" style="background-color: <?php echo $colore; ?>" @click="setActive(<?php echo $counter; ?>)">
								<span><?php echo $anno; ?></span>
							</div>
							<?php
							++$counter;
						endwhile;
						?>
					</div>
				</template>
				<?php
				$counter = 0;

				while ( have_rows( 'scheda' ) ) :

					the_row();
					$anno   = get_sub_field( 'anno' );
					$testo  = get_sub_field( 'testo' );
					$img    = get_sub_field( 'immagine_etichetta' );
					$colore = get_sub_field( 'colore_etichetta' );

					?>
					<div x-show="$store.header.navigationMode === 'desktop' || active == <?php echo $counter; ?>" x-transition data-entry class="transition-all overflow-hidden duration-200 p-gap md:p-2gap origin-center mdd:!w-full md:w-[111px] relative" style="background-color: <?php echo $colore; ?>" @mouseenter="setActive(<?php echo $counter; ?>)">
						<div data-content class="md:h-full md:absCenter md:p-4gap">
							<div class="w-full md:h-full transition-opacity duration-500" :class="active == <?php echo $counter; ?> ? 'opacity-100 delay-200' : 'opacity-0'">
								<?php
								new \Components\Title( 'titolo', '', 'title-lg mb-gap', false );
								?>
								<span><?php echo $anno; ?></span>
								<div class="w-[280px] max-w-full mt-gap prose !text-white"><?php echo $testo; ?></div>
								<?php Utils::SimpleACFImg( $img, 'full', ' w-[350px] mdd:ml-auto md:w-[350px] max-w-full md:max-w-[39%] md:absolute md:bottom-gap md:right-2gap h-auto mt-gap' ); ?>
							</div>
						</div>
						<span class="mdd:hidden w-[111px] text-center right-0 bottom-0 absolute block py-hhgap"><?php echo $anno; ?></span>
					</div>
					<?php
					++$counter;
				endwhile;
				?>
			</section>
			<?php

		endif;

		echo $this->containerClose;
	}
}
