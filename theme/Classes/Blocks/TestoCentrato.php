<?php
/**
 * TestoCentrato Block
 *
 * @author Alessio Pangos
 */
namespace Classes\Blocks;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class TestoCentrato extends BaseBlock {

	public function __construct( $getField = false, $block = null ) {
		parent::__construct( $getField, $block );
	}

	public function render() {

		$this->setup();

		echo $this->container;
		new \Components\Text('testo_centrato', ' text-center text-xl leading-[40px] md:text-2xl md:leading-[50px]');

		echo $this->containerClose;

	}

}
