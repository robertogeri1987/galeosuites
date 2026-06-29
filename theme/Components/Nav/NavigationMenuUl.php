<?php
/**
 * NavigationUl Component
 *
 * @author Alessio Pangos
 */
namespace Components\Nav;

// If menu file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class NavigationMenuUl {

	public function __construct( $nav ) {
		?>
		<ul class="<?php echo $nav->navigationMenuUlClasses; ?>">
			<?php
			if ( have_rows( $nav->prefix . 'menu_item', 'options' ) ) :
				while ( have_rows( $nav->prefix . 'menu_item', 'options' ) ) :
					the_row();

					// Controlla se è una voce per soli utenti loggati o meno
					if ( is_user_logged_in() ) {
						$skipField = get_sub_field( 'logged_out_only' );
					} else {
						$skipField = get_sub_field( 'logged_in_only' );
					}

					if ( ! $skipField ) :
						if ( get_sub_field( 'special_item' ) ) {
							$nav->specialItem( get_sub_field( 'special_item_type' ) );
						} else {
							new NavigationItem( $nav );
						}
					endif;

				endwhile;
			endif;

			// "Prenota" button as the last item of the primary menu.
			if ( $nav->prefix === '' ) {
				ReservationButton::button( $nav );
			}
			?>
		</ul>
		<?php
		if ( $nav->prefix === '' ) {
			ReservationButton::popup();
		}
	}
}
