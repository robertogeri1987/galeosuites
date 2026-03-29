<?php
/**
 * A Very Flexible Popup, can be a regular popup or a slider
 *
 * @author Alessio Pangos
 * @version 1.0
 */
namespace Classes\Core;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class APWPPopup {

	private static $initialized = false;

	protected $popupID;
	protected $pagesIn;
	protected $pagesNotIn;

	public function __construct() {

		$this->popupID = 0;

		if ( have_rows( 'apwp_popup', 'options' ) ) {

			$renderPopups = false;

			while ( have_rows( 'apwp_popup', 'options' ) ) :
				the_row();

				if ( get_sub_field( 'abilitato' ) ) {
					$renderPopups = true;
				}

			endwhile;

			// Only execute the code and enqueue scripts if at least one popup is enabled
			if ( $renderPopups ) :

				if ( ! self::$initialized ) {
					$this->init();
				}

				while ( have_rows( 'apwp_popup', 'options' ) ) :
					the_row();

					if ( get_sub_field( 'abilitato' ) ) :

						if ( get_sub_field( 'mostra_solo_in_alcune_pagine' ) ) {

							$this->pagesIn = get_sub_field( 'scegli_pagine' );
							if ( in_array( get_the_ID(), $this->pagesIn ) ) {
								$this->render();
							}
						} elseif ( get_sub_field( 'mostra_in_tutte_le_pagine_eccetto' ) ) {

							$this->pagesNotIn = get_sub_field( 'scegli_pagine_escluse' );
							if ( ! in_array( get_the_ID(), $this->pagesIn ) ) {
								$this->render();
							}
						} else {

							$this->render();

						}

					endif;

					++$this->popupID;

				endwhile;

			endif;

		}
	}

	public function init() {

		$this->localize_scripts();
		$this->enqueue_scripts();

		self::$initialized = true;
	}

	public function localize_scripts() {

		// Localize the script only the first time it is loaded on a page

		$popupData = array();

		if ( have_rows( 'apwp_popup', 'options' ) ) {

			while ( have_rows( 'apwp_popup', 'options' ) ) :
				the_row();

				$setCookie                                = get_sub_field( 'imposta_cookie' );
				$cookieSet                                = false;
				$alwaysShow                               = false;
				$isSlider                                 = false;
				get_sub_field( 'abilitato' ) ? $abilitato = true : $abilitato = false;
				$delay                                    = (int) get_sub_field( 'ritardo_popup' );

				if ( get_sub_field( 'tipo_popup' ) === 'Slider' ) {

					$isSlider = true;

					if ( is_front_page() ) {
						$alwaysShow = true;
					}
				}

				if ( $setCookie ) {

					$cookieName = 'ap-wp-tw_' . get_sub_field( 'nome_cookie' );
					$duration   = (int) get_sub_field( 'durata_cookie' );

					if ( isset( $_COOKIE[ $cookieName ] ) ) {
						$cookieSet = true;
					}
				}

				array_push(
					$popupData,
					array(
						'cookieSet'     => $cookieSet,
						'cookieName'    => $setCookie ? $cookieName : '',
						'durationHours' => $setCookie ? $duration : '',
						'alwaysShow'    => $alwaysShow,
						'isSlider'      => $isSlider,
						'delay'         => $delay,
						'abilitato'     => $abilitato,
					)
				);

			endwhile;

			// Localize Our Script so we can use `ajax_url`
			wp_localize_script(
				'ap-wp-theme-tw-script',
				'popupParams',
				array(
					'popupData' => $popupData,
				)
			);

		}
	}

	public function enqueue_scripts() {

		add_action( 'wp_enqueue_scripts', array( $this, 'localize_scripts' ) );
	}

	public function render() {

		$tipoPopup = get_sub_field( 'tipo_popup' );

		if ( $tipoPopup === 'Normale' ) {

			?>
			<div class="popup popup__grabber" id="popup_<?php echo $this->popupID; ?>" data-popupindex="<?php echo $this->popupID; ?>">
				<div class="popup__content">
					<div class="popup__right">
						<a data-popup="popup_<?php echo $this->popupID; ?>" href="#" class="popup__close">&times;</a>
						<div class="popup__form-container">
							<?php the_sub_field( 'contenuto_popup' ); ?>
						</div>
					</div>
				</div>
			</div>
			<?php

		}

		if ( $tipoPopup === 'Slider' ) {

			?>
			<div data-popup="popup_<?php echo $this->popupID; ?>" class="popup__sticky-button openpopup--info">
				<?php ap_svg( 'arrow-left-carousel' ); ?>
			</div>
			<div class="popup--info popup__grabber" id="popup_<?php echo $this->popupID; ?>" data-popupindex="<?php echo $this->popupID; ?>">
				<a href="#" class="popup__close--info"><?php ap_svg( 'plusnoborder' ); ?></a>
				<div class="popup__content popup__content--info">
					<div class="popup__slider-container">
						<?php

						if ( have_rows( 'elemento_del_popup', 'options' ) ) :

							$this->renderSlider();

						endif;

						?>
					</div>
				</div>

			</div>
			<?php

			$count = 0;

			while ( have_rows( 'elemento_del_popup', 'options' ) ) :
				the_row();

				?>

				<div class="popup" id="popup_sub<?php echo $this->popupID . '_' . $count; ?>">
					<div class="popup__content">
						<div class="popup__right">
							<a data-popup="popup_sub<?php echo $this->popupID . '_' . $count; ?>" href="#" class="popup__close">&times;</a>
							<div class="popup__form-container">
								<?php the_sub_field( 'testo_in_popup' ); ?>
							</div>
						</div>
					</div>
				</div>

				<?php

				++$count;

			endwhile;

		}
	}

	public function renderSlider() {

		echo '<div id="popup-swiper" class="swiper-container">';
			echo '<div class="acf-slider__container--carousel-popup swiper-wrapper">';

				$count = 0;

		while ( have_rows( 'elemento_del_popup', 'options' ) ) :
			the_row();

			?>
					<div data-popup="popup_sub<?php echo $this->popupID . '_' . $count; ?>" class="intro-block__link swiper-slide openpopup">
						<div class="intro-block__text intro-block__text--red intro-block__text--hovered">
							<div class="intro-block__title">
						<?php the_sub_field( 'titolo' ); ?>
							</div>
							<div class="intro-block__description">
						<?php the_sub_field( 'sottotitolo' ); ?>
							</div>
					<?php ap_svg( 'plusnoborder' ); ?>
						</div>
					</div>
					<?php

					++$count;

				endwhile;

			echo '</div>';
		?>
			<?php
			echo '</div>';

			$countSlides = 0;

			while ( have_rows( 'elemento_del_popup', 'options' ) ) :
				the_row();
				if ( $countSlides === 4 ) :
					?>
					<div class="acf-slider__arrow--left popup__arrow-left carousel-prev">
						<?php ap_svg( 'arrow-left-carousel' ); ?>
					</div>
					<div class="acf-slider__arrow--right popup__arrow-right carousel-next">
						<?php ap_svg( 'arrow-right-carousel' ); ?>
					</div>
					<?php
				endif;
				++$countSlides;
		endwhile;
	}
}
