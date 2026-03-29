<?php
/**
 * Core ACF Posts Slider Class clone
 *
 * @author Alessio Pangos
 */
namespace Classes\Blocks;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class PostsSlider extends \Classes\Core\ACFSlider {

	public function __construct( $getField = false, $block = null ) {

		parent::__construct( $getField, $block );
		$this->postsSlider  = true;
		$this->sliderType   = parent::ACF( 'tipo_di_slider' );
		$this->customChoice = parent::ACF( 'scegli' );
		$this->posts        = array();
		$this->postType     = parent::ACF( 'tipo_di_post' );
		$this->autoPlay     = parent::ACF( 'autoplay' );
		$this->wrapperClass = '';
		if ( $this->autoPlay ) {
			$this->autoPlay = 'true';
		}
	}

	public function getSlides() {

		if ( ! $this->customChoice && empty( $this->posts ) ) {

			$this->posts = new \WP_Query(
				array(
					'post_type'      => $this->postType,
					'posts_per_page' => parent::ACF( 'numero_massimo' ),
					'orderby'        => parent::ACF( 'ordina_per' ),
					'order'          => parent::ACF( 'ordina' ),
				)
			);

		}

		if ( $this->customChoice ) {

			$this->posts = new \WP_Query(
				array(
					'post_type'      => array( $this->postType ),
					'post__in'       => parent::ACF( 'scelta_manuale' ),
					'posts_per_page' => -1,
					'orderby'        => 'post__in',
				)
			);

		}

		if ( $this->posts->have_posts() ) :

			while ( $this->posts->have_posts() ) :
				$this->posts->the_post();

				( $this->autoPlay ) ? $slideDuration = parent::ACF( 'slide_duration' ) : $slideDuration = false;
				if ( $slideDuration ) {
					$slideDuration = ' data-swiper-autoplay="' . $slideDuration . '"';
				}

				?>
				<div class="acf-slider__slide swiper-slide"<?php echo $slideDuration; ?>>
					<?php echo get_template_part( 'template-parts/archive', $this->postType ); ?>
				</div>
				<?php

				++$this->numSlides;

			endwhile;

		endif;

		wp_reset_postdata();
	}
}
