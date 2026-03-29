<?php
/**
 * Image Block
 *
 * @author Alessio Pangos
 */
namespace Classes\Blocks;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Image extends BaseBlock {

	protected $link;

	public function __construct( $getField = false, $block = null ) {
		parent::__construct( $getField, $block );
		$this->link = Utils::GetLinkAndTarget( 'link_immagine', self::$getField );
	}

	public function render() {

		$this->setup();

		echo $this->container;

		Utils::LinkOpen( $this->link, 'w-full' );

			new \Components\Image( 'blocco_immagine', 'w-full' );

		Utils::LinkClose( $this->link );

		echo $this->containerClose;
	}
}
