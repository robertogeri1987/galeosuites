<?php
/**
 * Core ACF Slider Class clone
 *
 * @author Alessio Pangos
 */
namespace Classes\Blocks;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Slider extends \Classes\Core\ACFSlider {

	public function __construct( $getField = false ) {
		parent::__construct( $getField );
	}

}
