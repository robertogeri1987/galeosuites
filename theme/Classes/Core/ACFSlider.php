<?php

/**
 *
 *  ACF Pro Slider
 *
 *  @author Alessio Pangos
 *  @version 2.0
 *  Custom Slider based on ACF Pro and Swiper Slider JS (requires both to function)
 */

namespace Classes\Core;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class ACFSlider extends \Classes\Blocks\BaseBlock {

	public $currentPage;
	public $numSlides;
	public $sliderType;
	public $autoPlay;
	public $ownTextClasses;
	public $fixedTextClasses;
	public $singleImgClasses;
	public $carouselImgClasses;
	public $singleSlideClasses;
	public $carouselSlideClasses;
	public $arrowClasses;
	public $arrowLeftClasses;
	public $arrowRightClasses;
	public $arrowSvgClasses;
	public $lazy;
	public $postsSlider;
	public $customChoice;
	public $posts;
	public $postType;
	public $wrapperClass;
	public $screenHeight;

	public static function ACF( $field ) {
		return parent::ACF( $field );
	}

	public function __construct( $getField = false, $block = null ) {
		parent::__construct( $getField, $block );

		if ( $getField ) {
			parent::$getField = true;
		}

		$this->currentPage = get_the_ID();
		if ( is_front_page() ) {
			$this->currentPage = get_option( 'page_on_front' );
		}
		$this->inTemplate   = false;
		$this->wrapperClass = ''; // used in products carousels by PostsSlider class
		$this->screenHeight = false;
		$this->sliderType   = parent::ACF( 'tipo_slider' );
		$this->autoPlay     = parent::ACF( 'autoplay_acf_slider' );
		$this->isBlock      = true;

		$this->ownTextClasses       = 'absolute block z-[2] bottom-gap left-1/2 transform -translate-x-1/2 text-center';
		$this->fixedTextClasses     = $this->ownTextClasses;
		$this->singleImgClasses     = 'w-full h-full object-cover';
		$this->carouselImgClasses   = '';
		$this->singleSlideClasses   = '';
		$this->carouselSlideClasses = ' max-h-none h-auto';
		$this->arrowClasses         = ' acf-slider__arrow !w-[50px] !h-[50px] opacity-70 transition-opacity duration-400 cursor-pointer top-1/2 transform -translate-y-1/2 hover:opacity-100 after:hidden before:hidden';
		$this->arrowLeftClasses     = $this->arrowClasses . '';
		$this->arrowRightClasses    = $this->arrowClasses . '';
		$this->arrowSvgClasses      = '!w-[50px] !h-[50px] fill-tertiary';
		$this->lazy                 = true;
	}

	public function getSlides() {

		( $this->sliderType === 'Single' ) ? $imgClass     = $this->singleImgClasses : $imgClass = $this->carouselImgClasses;
		( $this->sliderType === 'Single' ) ? $slideClasses = $this->singleSlideClasses : $slideClasses = $this->carouselSlideClasses;

		if ( have_rows( 'slide', $this->currentPage ) ) :

			while ( have_rows( 'slide', $this->currentPage ) ) :
				the_row();

				( $this->autoPlay ) ? $slideDuration = get_sub_field( 'slide_duration' ) : $slideDuration = false;
				if ( $slideDuration ) {
					$slideDuration = ' data-swiper-autoplay="' . $slideDuration . '"';
				}
				$link      = get_sub_field( 'slider_slide_link' );
				$linkArray = Utils::GetLinkAndTarget( 'slider_slide_link', false );
				$link      = $linkArray;
				$target    = '';
				if ( $link ) {
					$target = $linkArray['target'];
				}
				$openSlide  = '';
				$closeSlide = '';

				if ( $link && array_key_exists( 'url', $link ) ) {
					$openSlide  = '<a href="' . $link['url'] . '"' . $target . ' class="swiper-slide' . $slideClasses . '"' . $slideDuration . '>';
					$closeSlide = '</a>';
				} else {
					$openSlide  = '<div class="swiper-slide' . $slideClasses . '"' . $slideDuration . '>';
					$closeSlide = '</div>';
				}

				echo $openSlide;

				if ( get_sub_field( 'slide_type' ) === 'Video' ) :
					$poster = get_sub_field( 'slide_video_cover' );
					?>
						<video class="lazyload h-screen w-full object-cover"
						<?php
						if ( $poster ) {
							echo 'data-poster="' . $poster['url'] . '" ';}
						?>
						autoplay="" preload="none" muted="" loop="" id="videoslide" playsinline>
							<source data-src="<?php echo get_sub_field( 'slide_video_url' ); ?>" type="video/mp4">
						</video>
					<?php

				else :

					new \Components\Image( 'slide_image', ' w-full h-full object-cover', false, false, '', $this->lazy );

				endif;

				if ( get_sub_field( 'slide_has_text' ) ) :
					new \Components\Text( 'slide_text', ' ' . $this->ownTextClasses, false );
				endif;

				echo $closeSlide;

				++$this->numSlides;

			endwhile;

		endif;
	}

	public function singleSlider() {

		?>
		<div class="acf-slider__container<?php echo $this->screenHeight ? ' h-screen' : ''; ?> swiper-container relative overflow-hidden" data-autoplay="<?php echo $this->autoPlay; ?>">

			<div class="acf-slider swiper-wrapper<?php echo $this->wrapperClass; ?>">

				<?php

				$this->numSlides = 0;
				$this->getSlides();

				if ( get_field( 'slider_has_fixed_text', $this->currentPage ) ) :
					$text = get_field( 'slider_fixed_text_testo_blocco', $this->currentPage );
					if ( is_array( $text ) && array_key_exists( 'testo_blocco', $text ) ) {
						$text = $text['testo_blocco'];
					}
					new \Components\Text( 'slider_fixed_text', ' ' . $this->fixedTextClasses, true, $text );
				endif;

				?>

			</div>

			<?php if ( $this->numSlides > 1 ) : ?>
				<div class="acf-slider__arrow--left swiper-button-prev<?php echo $this->arrowLeftClasses; ?>"><?php ap_svg( 'arrow-left-carousel', null, $this->arrowSvgClasses ); ?></div>
				<div class="acf-slider__arrow--right swiper-button-next<?php echo $this->arrowRightClasses; ?>"><?php ap_svg( 'arrow-right-carousel', null, $this->arrowSvgClasses ); ?></div>
			<?php endif; ?>

			<div class="acf-slider__bullets swiper-pagination"></div>

		</div>

		<?php
	}

	public function carouselSlider() {

		?>
		<div class="acf-slider__container--carousel-outer relative">

			<div class="acf-slider__container--carousel relative swiper-container" data-autoplay="<?php echo $this->autoPlay; ?>">

				<div class="swiper-wrapper<?php echo $this->wrapperClass; ?>">

					<?php

					$this->numSlides = 0;
					$this->getSlides();

					?>

				</div>

			</div>

			<?php if ( $this->numSlides > 1 ) : ?>
				<div class="acf-slider__arrow--left swiper-button-prev<?php echo $this->arrowLeftClasses; ?>"><?php ap_svg( 'arrow-left-carousel', null, $this->arrowSvgClasses ); ?></div>
				<div class="acf-slider__arrow--right swiper-button-next<?php echo $this->arrowRightClasses; ?>"><?php ap_svg( 'arrow-right-carousel', null, $this->arrowSvgClasses ); ?></div>
			<?php endif; ?>

			<div class="acf-slider__bullets swiper-pagination"></div>

		</div>

		<?php
	}

	public function render() {

		$this->setup();

		if ( $this->isBlock ) {
			echo $this->container;
		}

		if ( $this->autoPlay ) {
			$this->autoPlay = 'true';
		}

		if ( $this->sliderType === 'Single' ) {

			$this->singleSlider();
		} else {

			$this->carouselSlider();
		}

		if ( $this->isBlock ) {
			echo $this->containerClose;
		}
	}
}
