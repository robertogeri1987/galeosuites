<?php
/**
 * Image Component
 *
 * @author Alessio Pangos
 */
namespace Components;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Image extends BaseComponent {

	public function __construct( $prefix = '', $classes = '', $getField = true, $forcedPrefix = false, $additionalAttributes = '', $lazyload = true, $pitcureClasses = '' ) {
		parent::__construct( $getField );
		$image = false;
		if ( $forcedPrefix ) {
			$image = $forcedPrefix;
		} else {
			$image = parent::ACF( $prefix );
		}
		if ( \is_array( $image ) ) {
			$deskImg = \array_key_exists( 'immagine', $image ) ? $image['immagine'] : null;
			$tabImg  = \array_key_exists( 'immagine_tablet', $image ) ? $image['immagine_tablet'] : null;
			$mobImg  = \array_key_exists( 'immagine_mobile', $image ) ? $image['immagine_mobile'] : null;
			Utils::ResponsiveACFPicture( $deskImg, $classes, $tabImg, $mobImg, $lazyload, $additionalAttributes, $pitcureClasses );
		}
	}
}
