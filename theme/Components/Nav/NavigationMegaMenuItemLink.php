<?php
/**
 * NavigationMegaMenuItemLink Component
 *
 * @author Alessio Pangos
 */
namespace Components\Nav;

// If menu file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class NavigationMegaMenuItemLink {

	public function __construct( $nav, $addClasses = '' ) {
		$nav->isCurrent         = '';
		$nav->currentLink       = Utils::GetLinkAndTarget( 'link_megamenu', false );
		$nav->currentLinkTarget = '';

		if ( $nav->currentLink ) {
			$nav->currentLinkTarget = $nav->currentLink['target'];
			$nav->getIsCurrent( $nav->currentLink );
		}
		if ( $nav->currentLink && array_key_exists( 'url', $nav->currentLink ) && $nav->currentLink['url'] ) :
			?>
			<a
				class="title-lg--menu font-medium transition-all duration-500 hover:text-primary <?php echo $nav->isCurrent ? $nav->isCurrentSubClasses : '' . $addClasses; ?>"
				itemprop="url"
				href="<?php echo $nav->currentLink['url']; ?>"
				<?php echo $nav->currentLinkTarget; ?>
				<?php $nav->isCurrent = ''; ?>
				data-megamenu-itemlink
			>
				<?php the_sub_field( 'title_megamenu' ); ?>
			</a>
			<?php
		else :
			?>
			<span class="title-lg--menu font-medium transition-all duration-500 hover:text-primary <?php echo $nav->isCurrent ? $nav->isCurrentSubClasses : '' . $addClasses; ?>" itemprop="name" data-megamenu-itemlink>
				<?php the_sub_field( 'title_megamenu' ); ?>
			</span>
			<?php
		endif;
	}
}
