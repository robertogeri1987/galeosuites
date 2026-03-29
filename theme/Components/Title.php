<?php
/**
 * Title Component
 *
 * @author Alessio Pangos
 */
namespace Components;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Title extends BaseComponent {

	public function __construct( $prefix = '', $additionalClasses = '', $defaultSize = 'title-md', $getField = true, $additionalAttributes = '' ) {
		parent::__construct( $getField );
		$group = parent::ACF( $prefix );
		Utils::titleGroupACF( $group, $additionalClasses, $defaultSize, $additionalAttributes );
	}
}
