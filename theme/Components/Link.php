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

class Link extends BaseComponent {

	public function __construct( $prefix = '', $classes = '', $getField = true, $forcedContent = false, $forcedPrefix = false, $additionalAttributes = '' ) {
		parent::__construct( $getField );
		$linkArray = Utils::GetLinkAndTarget( $prefix, $getField, $forcedPrefix );
		Utils::LinkOpen( $linkArray, $classes, $forcedContent, false, $additionalAttributes );
		Utils::LinkClose( $linkArray );
	}
}
