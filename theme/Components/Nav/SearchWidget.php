<?php
/**
 * SearchWidget Component
 *
 * @author Alessio Pangos
 */
namespace Components\Nav;

// If menu file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class SearchWidget {

	public function __construct() {

		$searchform = get_search_form( false );
		?>
			<li data-predictive-search-container class="list-none desk:w-auto desk:ml-auto desk:flex-1 desk:px-hhgap">
				<?php echo $searchform; ?>
			</li>
		<?php
	}
}
