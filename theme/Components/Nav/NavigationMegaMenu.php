<?php
/**
 * NavigationMegaMenu Component
 *
 * @author Alessio Pangos
 */
namespace Components\Nav;

// If menu file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class NavigationMegaMenu {

	public function __construct( $nav ) {
		?>
		<div class="<?php echo $nav->megaMenuClasses; ?>" x-ref="megaMenu">
			<template x-if="$store.header.navigationMode === 'mobile'">
				<div class="py-gap border-y border-black flex items-center justify-center relative mb-gap md:mb-4gap">
					<?php ap_svg( 'arrow-right', '', 'w-[20px] min-w-[20px] h-[20px] fill-none stroke-current left-gap yCenter transform -rotate-180', null, ' @click="open = false"' ); ?>
					<?php
					$linkArray        = Utils::GetLinkAndTarget( 'link_megamenu', false );
					$nav->currentLink = $linkArray;
					if ( $nav->currentLink ) :
						?>
						<a
							class="base-text"
							href="<?php echo $nav->currentLink['url']; ?>"<?php echo $nav->currentLinkTarget; ?>
							itemprop="url"
						>
						<?php the_sub_field( 'title' ); ?>
						</a>
						<?php
					else :
						?>
						<span class="base-text" itemprop="name">
							<?php the_sub_field( 'title' ); ?>
						</span>
						<?php
					endif;
					?>
				</div>
			</template>
			<?php
			if ( have_rows( 'row_megamenu', 'options' ) ) :
				while ( have_rows( 'row_megamenu', 'options' ) ) :
					the_row();

					$cols       = get_sub_field( 'choose_length' ) ? ' desk:grid-cols-' . get_sub_field( 'column_length' ) : ' grid-flow-col';
					$addClasses = get_sub_field( 'classi_aggiuntive' ) ? ' ' . get_sub_field( 'classi_aggiuntive' ) : '';

					?>
					<div class="layout-container desk:pb-2gap desk:pt-gap mx-auto grid gap-gap<?php echo $addClasses . $cols; ?>">
						<span class="topCenter block w-[calc(theme(spacing.layoutContent))] desk:border-t desk:border-t-black"></span>
						<?php
						if ( have_rows( 'column_megamenu', 'options' ) ) :
							while ( have_rows( 'column_megamenu', 'options' ) ) :
								the_row();
								$addClasses = get_sub_field( 'classi_aggiuntive_colonna' ) ? ' ' . get_sub_field( 'classi_aggiuntive_colonna' ) : '';
								$hoverFx    = get_sub_field( 'effetto' );
								if ( ! get_sub_field( 'no_auto_rows_min' ) ) {
									$addClasses .= ' auto-rows-min';
								}
								?>
								<ul class="grid grid-cols-1 gap-hhgap <?php echo $addClasses; ?>"<?php echo $hoverFx ? ' data-hover-fx-col' : ''; ?>>
									<?php
									new NavigationMegaMenuItem( $nav );
									?>
								</ul>
								<?php
								endwhile;
							endif;
						?>
					</div>
					<?php

				endwhile;
			endif;
			?>
		</div>
		<?php
	}
}
