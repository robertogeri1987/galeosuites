<?php
/**
 * Navigation Item Component
 *
 * @author Alessio Pangos
 */
namespace Components\Nav;

// If menu file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class NavigationItem {

	public function __construct( $nav ) {
		$nav->isCurrent         = '';
		$nav->currentLink       = Utils::GetLinkAndTarget( 'link', false );
		$nav->currentLinkTarget = '';

		if ( $nav->currentLink ) {
			$nav->currentLinkTarget = $nav->currentLink['target'];
			$nav->getIsCurrent( $nav->currentLink );
		}

		$hasSubmenu = get_sub_field( 'has_submenu' );
		$addClasses = get_sub_field( 'additional_classes' );

		?>
			<li
				class="<?php echo $nav->navigationItemClasses . $addClasses; ?>"
				x-data="menuItem"
				x-ref="menuItem"
			>
				<?php
				new NavigationLink( $nav );
				$nav->isCurrent = '';

				if ( $hasSubmenu ) :

					$menuType = get_sub_field( 'submenu_type' );

					if ( $menuType === 'MegaMenu' ) {
						if ( get_sub_field( 'type_megamenu' ) === 'Colonne' ) {
							new NavigationMegaMenu( $nav );
						}
					}

				endif;
				?>
			</li>
		<?php
	}
}
