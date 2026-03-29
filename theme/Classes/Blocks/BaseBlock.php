<?php
/**
 * Simple Wysiwig Text Block
 *
 * @author Alessio Pangos
 */
namespace Classes\Blocks;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class BaseBlock {

	protected static $getField = false;

	protected $block;
	protected $inTemplate;
	protected $class;
	protected $marginTop;
	protected $reducedMargin;
	protected $marginBottom;
	protected $container;
	protected $containerClose;
	protected $innerContainerOpen;

	public static function ACF( $field ) {
		if ( self::$getField ) {
			return get_field( $field );
		} else {
			return get_sub_field( $field );
		}
	}

	public function __construct( $getField, $block ) {

		self::$getField           = $getField;
		$this->block              = $block;
		$this->inTemplate         = false;
		$this->class              = '';
		$this->marginTop          = '';
		$this->reducedMargin      = '';
		$this->marginBottom       = '';
		$this->container          = '';
		$this->containerClose     = '';
		$this->innerContainerOpen = '';
	}

	public function setup() {

		if ( $this->inTemplate ) {
			return;
		}

		$ext      = '';
		$dataName = '';

		if ( self::$getField ) :
			$id = '';
			if ( ! empty( $this->block['anchor'] ) ) {
				$id = 'id="' . esc_attr( $this->block['anchor'] ) . '" ';
			}
			if ( ! empty( $this->block['name'] ) ) {
				$dataName = 'data-blockname="' . \str_replace( '/', '-', $this->block['name'] ) . '" ';
			}

			// Create class attribute allowing for custom "className" and "align" values.
			$this->class = 'base-block';
			if ( ! empty( $this->block['className'] ) ) {
				$this->class .= ' ' . $this->block['className'];
			}
			if ( ! empty( $this->block['align'] ) ) {
				$this->class .= ' align' . $this->block['align'];
			} else {
				$this->class .= ' mx-auto overflow-hidden';
			}
		else :
			$this->class = self::ACF( 'classe_aggiuntiva_container' );
			$id          = self::ACF( 'id_container' );
			if ( $id ) {
				$id = 'id="' . $id . '" ';
			}
			$ext                     = self::ACF( 'estensione_container' );
			$ext === 'normal' ? $ext = '' : $ext = ' ' . $ext;
		endif;

		( $this->class || $ext ) ? $this->innerContainerOpen = '<div class="' . $this->class . $ext . '">' : $this->innerContainerOpen = '';

		$this->marginTop     = self::ACF( 'margine_in_alto' ) ? ' mt-svgap md:mt-vgap' : '';
		$this->marginBottom  = self::ACF( 'margine_in_basso' ) ? ' pb-svgap md:pb-vgap' : '';
		$this->reducedMargin = '';
		if ( self::ACF( 'margine_ridotto' ) ) {
			$this->reducedMargin = ' mt-gap md:mt-svgap';

			if ( $this->marginBottom ) {
				$this->reducedMargin .= ' pb-gap md:pb-svgap';
			}
			$this->marginTop    = '';
			$this->marginBottom = '';
		}

		$this->container = '<section ' . $dataName . $id . 'class="ap-wp-theme-tw-block w-full block' . $this->marginTop . $this->marginBottom . $this->reducedMargin . '">' . $this->innerContainerOpen;

		( $this->innerContainerOpen ) ? $this->containerClose = '</div></section>' : $this->containerClose = '</section>';
	}
}
