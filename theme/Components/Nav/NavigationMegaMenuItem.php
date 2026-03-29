<?php
/**
 * NavigationMegaMenuItem Component
 *
 * @author Alessio Pangos
 */
namespace Components\Nav;

// If menu file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class NavigationMegaMenuItem {

	public function __construct( $nav ) {
		if ( have_rows( 'column_item', 'options' ) ) :
			$counter = 0;
			while ( have_rows( 'column_item', 'options' ) ) :
				the_row();
				$itemClasses       = '';
				$classes           = get_sub_field( 'stile_voce' ) . ' !mb-0';
				$additionalClasses = get_sub_field( 'additional_classes_megamenu' );
				$btnOnMobile       = get_sub_field( 'diventa_bottone_su_mobile' );
				$mobileToo         = get_sub_field( 'mostra_su_mobile' );

				if ( $mobileToo ) {
					$classes    .= ' mobile:relative';
					$itemClasses = ' mobile:text-white mobile:absolute mobile:left-gap mobile:bottom-gap z-[10] transition-all duration-500';
				}

				if ( $additionalClasses ) {
					$classes .= ' ' . $additionalClasses;
				}
				if ( $btnOnMobile ) :
					?>
					<template x-if="$store.header.navigationMode === 'desktop'">
					<?php
				endif;
				?>
				<li class="<?php echo $classes; ?>">
					<?php
					$nav->isCurrent = '';

					if ( get_sub_field( 'end_list' ) ) {
						echo '</ul><ul class="mt-gap">';
					}

					$itemType = get_sub_field( 'item_type_megamenu' );

					if ( $itemType === 'Testo/Link' ) {
						new NavigationMegaMenuItemLink( $nav, $itemClasses );
					}
					if ( $itemType === 'Immagine' ) {
						new NavigationMegaMenuItemImage( $nav );
					}
					if ( $itemType === 'Testo Libero' ) {
						Utils::ProseText( get_sub_field( 'free_text' ) );
					}
					if ( $itemType === 'Divisore' ) {
						echo '<div class="bg-black h-[1px] w-full my-hgap"></div>';
					}
					if ( $itemType === 'Ricerca' ) {
						?>
						<div class="relative desk:min-h-[180px] md:mt-gap" data-predictive-search-container>
							<?php
							echo get_search_form();
							?>
						</div>
						<?php
					}
					$hoverImg = get_sub_field( 'immagine_allhover' );
					if ( $hoverImg ) {
						?>
						<figure class="w-full desk:hidden<?php echo $mobileToo ? '' : ' mobile:hidden'; ?>">
							<?php Utils::SimpleACFImg( $hoverImg, 'full', ' desk:opacity-0 transition-opacity duration-500 desk:absolute desk:top-0 desk:left-0 object-cover w-full h-full' ); ?>
						</figure>
						<?php if ( $mobileToo ) : ?>
							<span class="h-1/2 w-full left-0 bottom-0 absolute menu-gradient z-[2] desk:hidden"></span>
						<?php endif; ?>
						<?php
					}
					?>
				</li>
				<?php
				if ( $btnOnMobile ) :
					?>
					</template>
					<template x-if="$store.header.navigationMode === 'mobile'">
						<button class="absolute bottom-0 left-0 py-gap bg-black text-white w-full text-center z-[99999]">
							<?php
							new NavigationMegaMenuItemLink( $nav, ' w-full h-full flex items-center justify-center' );
							?>
						</button>
					</template>
					<?php
				endif;
				++$counter;
			endwhile;
		endif;
	}
}
