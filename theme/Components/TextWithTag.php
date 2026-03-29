<?php
/**
 * TextWithTag Component
 *
 * @author Alessio Pangos
 */
namespace Components;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class TextWithTag extends BaseComponent {

	public function __construct( $prefix = '', $classes = '', $getField = true, $additionalAttributes = '' ) {
		parent::__construct( $getField );
		$group = parent::ACF( $prefix );
		if ( $group ) {
			Utils::DynamicTag( $group['tag_html'], $group['testo_con_tag'], $classes, $additionalAttributes );
		}
	}
}
