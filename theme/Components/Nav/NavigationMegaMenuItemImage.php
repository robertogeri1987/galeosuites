<?php
/**
 * NavigationMegaMenuItemImage Component
 *
 * @author Alessio Pangos
 */
namespace Components\Nav;

// If menu file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class NavigationMegaMenuItemImage {

	public function __construct( $nav ) {
		$nav->isCurrent   = '';
		$linkArray        = Utils::GetLinkAndTarget( 'image_link_megamenu', false );
		$nav->currentLink = $linkArray;
		if ( $nav->currentLink ) {
			$nav->currentLinkTarget = $linkArray['target'];
			$nav->getIsCurrent( $nav->currentLink );
		}

		if ( $nav->currentLink ) :
			?>
			<a class="" itemprop="url" href="<?php echo $nav->currentLink['url']; ?>"<?php echo $nav->currentLinkTarget; ?>>
			<?php
			if ( get_sub_field( 'title_megamenu' ) ) {
				echo get_sub_field( 'title_megamenu' );
			}
		endif;

		$listImg = get_sub_field( 'image_megamenu' );

		if ( $listImg ) :
			?>
			<figure class="relative w-full h-full">
				<?php Utils::SimpleACFImg( $listImg, 'full', ' w-full h-full object-cover' ); ?>
				<?php if ( $listImg['caption'] ) : ?>
					<figcaption><?php echo $listImg['caption']; ?></figcaption>
				<?php endif; ?>
				<?php Utils::ProseText( get_sub_field( 'image_megamenu_text' ) ); ?>
			</figure>
			<?php
		endif;

		if ( $nav->currentLink ) :
			?>
			</a>
			<?php
		endif;
	}
}
