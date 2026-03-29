<?php
/**
 * NavigationLink Component
 *
 * @author Alessio Pangos
 */
namespace Components\Nav;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class NavigationLink {

	public function __construct( $nav ) {

		if ( $nav->currentLink && array_key_exists( 'url', $nav->currentLink ) && $nav->currentLink['url'] !== '' ) :

			?>
			<a
				class="<?php echo $nav->navigationLinkClasses . $nav->isCurrent; ?>"
				href="<?php echo $nav->currentLink['url']; ?>"<?php echo $nav->currentLinkTarget; ?>
				itemprop="url"
				@click="handleClick($event)"

			>
				<?php
				the_sub_field( 'title' );
				new Indicator();
				?>
			</a>
			<?php

		else :

			?>
			<span class="<?php echo $nav->navigationLinkClasses; ?>" @click="toggle()">
				<?php
				the_sub_field( 'title' );
				new Indicator();
				?>
			</span>
			<?php

		endif;
	}
}
