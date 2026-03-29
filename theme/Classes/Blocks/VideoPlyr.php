<?php

/**
 * VideoPlyr Block
 *
 * @author Alessio Pangos
 */

namespace Classes\Blocks;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class VideoPlyr extends BaseBlock {

	public $cover;
	public $url;

	public function __construct( $getField = false, $block = null ) {
		parent::__construct( $getField, $block );
		$this->cover = parent::ACF( 'cover_video' );
		$this->url   = parent::ACF( 'url_video' );
	}

	public function render() {

		$this->setup();

		echo $this->container; ?>

		<video class="video-js vjs-default-skin w-full" preload="none" poster='<?php echo $this->cover['url']; ?>'>
			<source src="<?php echo $this->url; ?>" type='video/mp4' />
		</video>

		<?php
		echo $this->containerClose;
	}
}
