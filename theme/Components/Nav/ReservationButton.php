<?php
/**
 * Reservation Button Component
 *
 * Renders the "Prenota" menu button and the popup containing the
 * slope-reservations shortcode. The popup reuses the global popup
 * markup/behaviour handled by APWPPopup (openpopup / popup__close).
 *
 * @author Alessio Pangos
 */
namespace Components\Nav;

// If menu file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class ReservationButton {

	const POPUP_ID = 'popup_prenota';

	/**
	 * Render the menu item that triggers the popup.
	 */
	public static function button( $nav ) {
		?>
		<li class="<?php echo $nav->navigationItemClasses; ?> mobile:mx-gap mobile:py-gap desk:flex desk:items-center">
			<a href="#" class="openpopup base-button" data-popup="<?php echo esc_attr( self::POPUP_ID ); ?>">
				<?php esc_html_e( 'Prenota', 'ap-wp-theme' ); ?>
			</a>
		</li>
		<?php
	}

	/**
	 * Render the popup with the reservation shortcode.
	 * The language is taken from WPML, falling back to the site locale.
	 */
	public static function popup() {
		$lang = defined( 'ICL_LANGUAGE_CODE' ) ? ICL_LANGUAGE_CODE : substr( get_locale(), 0, 2 );
		?>
		<div class="popup" id="<?php echo esc_attr( self::POPUP_ID ); ?>">
			<div class="popup__content desk:w-[95vw] desk:max-w-[940px]">
				<div class="popup__right">
					<a data-popup="<?php echo esc_attr( self::POPUP_ID ); ?>" href="#" class="popup__close">&times;</a>
					<div class="popup__form-container w-full max-w-full overflow-x-auto">
						<?php echo do_shortcode( '[slope-reservations lang=' . esc_attr( $lang ) . ']' ); ?>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}
