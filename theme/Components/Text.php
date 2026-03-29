<?php
/**
 * Text Component
 *
 * @author Alessio Pangos
 */
namespace Components;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Text extends BaseComponent {

	public function __construct( $prefix = '', $additionalClasses = '', $getField = true, $forcedPrefix = false, $additionalAttributes = '' ) {
		parent::__construct( $getField );
		$text = false;
		if ( $forcedPrefix ) {
			$text = $forcedPrefix;
		} else {
			$text = parent::ACF( $prefix );
			if ( is_array( $text ) && array_key_exists( 'testo_blocco', $text ) ) {
				$text = $text['testo_blocco'];
			}
		}
		// if ( $additionalClasses && strpos( $additionalClasses, ' ' ) !== 0 ) {
		// $additionalClasses = ' ' . $additionalClasses;
		// }

		if ( is_string( $text ) ) {
			Utils::ProseText( $text, $additionalClasses, $id = '', $additionalAttributes );
		}
	}
}
