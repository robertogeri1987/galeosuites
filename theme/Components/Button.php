<?php
/**
 * Link Component
 *
 * @author Alessio Pangos
 */
namespace Components;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Button extends BaseComponent {

	public function __construct( $prefix = '', $additionalClasses = '', $getField = true, $forcedContent = false, $forcedPrefix = false, $additionalAttributes = '' ) {
		parent::__construct( $getField );
		$linkArray = Utils::GetLinkAndTarget( $prefix, $getField, $forcedPrefix );
		Utils::LinkOpen( $linkArray, 'base-button' . $additionalClasses, $forcedContent, false, $additionalAttributes );
		Utils::LinkClose( $linkArray );
	}
}
