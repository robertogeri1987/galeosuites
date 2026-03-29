<?php
/**
 * Base Component
 *
 * @author Alessio Pangos
 */
namespace Components;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class BaseComponent {

	protected static $getField = false;

	public static function ACF( $field ) {

		if ( self::$getField ) {
			return get_field( $field );
		} else {
			return get_sub_field( $field );
		}

	}

	public function __construct( $getField ) {

		self::$getField = $getField;

	}

}
