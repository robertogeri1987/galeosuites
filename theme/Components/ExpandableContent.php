<?php
/**
 * ExpandableContent Component
 *
 * @author Alessio Pangos
 */
namespace Components;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class ExpandableContent {

	public function __construct( $titleContent = '', $textContent = '', $additionalClasses = '', $additionalAttributes = '' ) {
		if ( $additionalClasses ) {
			$additionalClasses = ' ' . $additionalClasses;
		}
		if ( $additionalAttributes ) {
			$additionalAttributes = ' ' . $additionalAttributes;
		}
		?>
		<div class="exp<?php echo $additionalClasses; ?>"<?php echo $additionalAttributes; ?>>
			<div class="exp__toggler flex items-center justify-between cursor-pointer">
				<?php echo $titleContent; ?>
				<?php ap_svg( 'exp-arrow', null, 'exp-arrow w-[12px] h-[12px] stroke-black' ); ?>
			</div>
			<div class="exp__content overflow-hidden">
				<div class="exp__padding">
					<?php echo $textContent; ?>
				</div>
			</div>
		</div>
		<?php
	}
}
