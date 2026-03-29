<?php
/**
 * Title Block
 *
 * @author Alessio Pangos
 */
namespace Classes\Blocks;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Title extends BaseBlock {

	public function __construct( $getField = false, $block = null ) {
		parent::__construct( $getField, $block );
	}

	public function render() {

		$this->setup();

		echo $this->container;

		new \Components\Title( 'titolo_blocco_titolo', ' text-center', 'title-lg' );
		new \Components\TextWithTag( 'sottotitolo', 'text-center title-sm' );

		echo $this->containerClose;
	}
}
