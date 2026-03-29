<?php
/**
 * Flexible Archive Ajax Filters
 *
 * @author Alessio Pangos
 */
namespace Classes\Core;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class FlexibleArchiveFilters {

	public static $removalLinks = array();
	private static $initialized = false;
	public static $currentUrl;

	public $layout;
	public $forceNoMain;
	public $filterFunction;
	public $showAll;
	public $additionalUlClasses;
	public $isMultiFilter;
	public $directChoice;
	public $multiFilterData;
	public $checkbox;
	public $getTerm;
	public $termType;
	public $name;
	public $slugBased;
	public $isMain;
	public $currentParent;
	public $firstParent;
	public $expandAllTerms;
	public $showAllHigherLevel;
	public $skipSelf;
	public $hideIfChildren;
	public $excludedTerms;
	public $hideParent;
	public $forcedParent;
	public $currentQueriedObjectID;
	public $showTopParent;
	public $currentTerm;
	public $allTerms;
	public $currentTermsIDs;
	public $currentActiveState;
	public $checkboxHTML;
	public $removeIconHTML;
	public $removeIcon;
	public $arrowDown;
	public $arrowBack;
	public $filterClass;
	public $hiddenClass;
	public $continue;
	public $childTerms;
	public $removalFilterName;
	public $removalLink;
	public $type;
	public $currentLink;

	private static $index = array(
		'category'    => array( 0, 'Taxonomy' ),
		'add_cat'     => array( 1, 'Taxonomy' ),
		'post_tag'    => array( 2, 'Taxonomy' ),
		'product_cat' => array( 3, 'Taxonomy' ),
		'p_cat'       => array( 4, 'Taxonomy' ),
		'product_tag' => array( 5, 'Taxonomy' ),
	);

	public static function getIndex( $term = null ) {
		if ( $term ) {
			return self::$index[ $term ];
		}
		return var_dump( self::$index );
	}

	public static function setIndex( $term, $position ) {
		self::$index[ $term ] = $position;
	}

	/**
	 *
	 * Initialize Class
	 */
	private static function initialize() {

		global $wp;

		// Remove paging from URL if we are not on the first page to avoid 404
		if ( ! is_paged() ) :

			self::$currentUrl = home_url( add_query_arg( array( $_GET ), $wp->request ) );

		else :

			$fullUrl         = home_url( add_query_arg( array( $_GET ), $wp->request ) );
			$urlWithPaging   = explode( '/page', $fullUrl );
			$urlBeforePaging = $urlWithPaging[0];
			$urlAfterPaging  = explode( '?', $fullUrl );

			( ! empty( $urlAfterPaging[1] ) ) ? $urlAfterPaging = '/?' . $urlAfterPaging[1] : $urlAfterPaging = '';

			self::$currentUrl = $urlBeforePaging . $urlAfterPaging;

		endif;

		// Index names for the core WordPress taxonomies
		foreach ( self::$index as $defaultIndex => $value ) {

			$customValue = get_field( 'ap_wp_core_filter_name_' . $defaultIndex, 'options' );
			if ( $defaultIndex === 'add_cat' ) {
				$customValue = get_field( 'ap_wp_core_filter_name_category', 'options' );
			}
			if ( $defaultIndex === 'p_cat' ) {
				$customValue = get_field( 'ap_wp_core_filter_name_product_cat', 'options' );
			}

			if ( $customValue ) {

				self::$index[ $defaultIndex ][2] = $customValue;

			} else {

				$curTerm = $defaultIndex;

				if ( $defaultIndex === 'add_cat' ) {
					$curTerm = 'category';
				}
				if ( $defaultIndex === 'p_cat' ) {
					$curTerm = 'product_cat';
				}

				$tax = get_taxonomy( $curTerm );
				if ( $tax ) {
					$name                            = get_taxonomy_labels( $tax );
					self::$index[ $defaultIndex ][2] = $name->name;
				}
			}
		}

		// Add all filters added in admin to the index
		if ( have_rows( 'ap_wp_filtri_personalizzati', 'options' ) ) :

			while ( have_rows( 'ap_wp_filtri_personalizzati', 'options' ) ) :
				the_row();

				$type     = sanitize_text_field( get_sub_field( 'ap_wp_filter_type' ) );
				$index    = (int) get_sub_field( 'ap_wp_filter_index' );
				$sysName  = sanitize_text_field( get_sub_field( 'ap_wp_filter_system_name' ) );
				$name     = sanitize_text_field( get_sub_field( 'ap_wp_filter_name' ) );
				$queryVar = sanitize_text_field( get_sub_field( 'ap_wp_filter_query_var' ) );

				self::$index[ $queryVar ] = array( $index, $type, $name );

			endwhile;

		endif;

		$ajax_url = admin_url( 'admin-ajax.php' );
		wp_localize_script(
			'ap-wp-theme-tw-script',
			'coreParams',
			array(
				'url'        => $ajax_url,
				'nonce'      => wp_create_nonce( 'ap-flexible-filters' ),
				'index'      => self::$index,
				'currentUrl' => self::$currentUrl,
				'isAjax'     => get_field( 'caricamento_ajax_flexible_filters', 'options' ),
			)
		);

		add_action( 'ap_wp_after_content', array( '\Classes\Core\FlexibleArchiveFilters', 'ajax_loader' ) );

		self::$initialized = true;
	}

	public static function ajax_loader() {
		echo '<div class="archive-filters__ajax-loader"></div>';
	}

	/**
	 *
	 * Defaults
	 */
	public function __construct() {

		if ( ! self::$initialized ) {
			self::initialize();
		}

		// Defaults
		$this->layout                 = 'vertical';
		$this->filterFunction         = 'filter_taxonomy';
		$this->showAll                = false; // skip terms if not found in results from the current query
		$this->additionalUlClasses    = array();
		$this->isMultiFilter          = false;
		$this->directChoice           = false; // if multifliter, clicking on a filter immediately triggers filtering, instead of choosing with a button
		$this->multiFilterData        = '';
		$this->checkbox               = '';
		$this->getTerm                = 'product_cat';
		$this->termType               = 'product_cat';
		$this->name                   = __( 'Product categories', 'woocommerce' );
		$this->slugBased              = true; // Get query based on ids or slug - default for WordPress is based on slugs
		$this->isMain                 = false;
		$this->forceNoMain            = false;
		$this->currentParent          = 0;
		$this->firstParent            = false;
		$this->expandAllTerms         = false;
		$this->showAllHigherLevel     = false;
		$this->skipSelf               = false;
		$this->hideIfChildren         = true;
		$this->excludedTerms          = array();
		$this->hideParent             = false;
		$this->forcedParent           = false;
		$this->currentQueriedObjectID = 0;
		$this->showTopParent          = false;
		$this->currentTerm            = null;

		// Elements
		$this->removeIconHTML = ap_svg( 'close-outline', __( 'Remove filter', 'ap-wp-theme' ), 'w-[14px] h-[14px] ml-hhgap', true );
		$this->checkbox       = '';
		$this->checkboxHTML   = '<span class="archive-filters__bullet archive-filters__bullet--filled">' . ap_svg( 'bullet-filled', __( 'Choose filter', 'ap-wp-theme' ), 'w-[14px] h-[14px] ml-hhgap', true ) . '</span><span class="archive-filters__bullet archive-filters__bullet--empty">' . ap_svg( 'bullet-empty', __( 'Choose filter', 'ap-wp-theme' ), 'w-[14px] h-[14px] ml-hhgap', true ) . '</span>';
		$this->arrowDown      = ap_svg( 'arrow-down', __( 'Open Filter', 'ap-wp-theme' ), 'filter-open w-[14px] h-[14px] ml-auto transform -rotate-90 md:rotate-0 pointer-events-none transition-transform duration-500', true );
		$this->arrowBack      = ap_svg( 'arrow-down', __( 'Back', 'ap-wp-theme' ), 'w-[14px] h-[14px] mr-hhgap transform -rotate-90 pointer-events-none fill-white', true );
	}

	/**
	 *
	 * Gets the current parent term hierarchy until we reach a term with parent = '0'
	 */
	public function get_current_term_top_parent() {

		$this->allTerms = array();

		// Climb up the hierarchy until we reach a term with parent = '0'
		$parent = get_term_by( 'id', $this->currentParent, $this->termType );
		while ( $parent->parent != '0' ) {
			$term_id = $parent->parent;
			$parent  = get_term( $term_id, $this->termType );
		}
		array_push( $this->allTerms, $parent );
	}


	/**
	 *
	 * Get all terms in the current query
	 */
	public function get_all_current_terms() {

		global $wp, $posts;

		if ( $this->termType === 'p_cat' && $this->getTerm === 'p_cat' ) {
			$this->termType = 'product_cat';
		}

		// if firstParent is set, display all items that are children of the first parent of the current queried object
		if ( $this->currentParent ) :

			if ( $this->firstParent ) {
				$ancestors  = get_ancestors( $this->currentParent, $this->termType );
				$thisParent = reset( $ancestors );
			} else {
				$thisParent = $this->currentParent;
			}

			if ( $this->hideParent ) {
				array_push( $this->excludedTerms, $thisParent );
			}

			$this->allTerms = get_terms(
				array(
					'taxonomy'   => $this->termType,
					'hide_empty' => true,
					'parent'     => $thisParent,
				)
			);

		else :

			$this->allTerms = get_terms(
				array(
					'taxonomy'   => $this->termType,
					'hide_empty' => true,
				)
			);

		endif;

		$currentTermsIDs = array();

		if ( is_array( $posts ) ) :

			// Get all terms and parent terms in the current query
			foreach ( $posts as $currentPost ) :

				$parent   = array();
				$children = array();

				$term = get_the_terms( $currentPost->ID, $this->termType );

				// Get parent term of current item and all child terms
				if ( $term ) :
					foreach ( $term as $ter ) :
						array_push( $currentTermsIDs, $ter->term_id );
						if ( $ter->parent != 0 ) :
							$ancestors = get_ancestors( $ter->term_id, $this->termType );
							$parent[]  = end( $ancestors );
							array_push( $currentTermsIDs, end( $ancestors ) );
						else :
							$parent[] = $ter->term_id;
							array_push( $currentTermsIDs, $ter->term_id );
						endif;
					endforeach;
				endif;

				if ( $this->showAllHigherLevel ) :

					if ( $parent ) :
						foreach ( $parent as $par ) :
							$childTerms = get_terms(
								array(
									'taxonomy'   => $this->termType,
									'hide_empty' => true,
									'parent'     => $par,
								)
							);
							foreach ( $childTerms as $chi ) :
								array_push( $currentTermsIDs, $chi->term_id );
							endforeach;
						endforeach;
					endif;

				endif;

			endforeach;

		endif;

		$this->currentTermsIDs = array_unique( $currentTermsIDs );
	}

	/**
	 *
	 * Get all fields in the current query
	 */
	public function get_all_current_fields() {

		global $wp, $posts;

		$currentTermsIDs = array();

		$field = $this->termType;

		foreach ( $posts as $currentPost ) {
			if ( $currentPost->$field ) {
				array_push( $currentTermsIDs, $currentPost->$field );
			}
		}

		$this->currentTermsIDs = array_unique( $currentTermsIDs );
		$this->allTerms        = $this->currentTermsIDs;

		if ( \array_key_exists( 0, $this->allTerms ) ) {
			if ( preg_match( '~[0-9]+~', $this->allTerms[0][0] ) ) { // sort numeric if first term is numeric
				sort( $this->allTerms, SORT_NUMERIC );
			} else {
				sort( $this->allTerms );
			}
		}
	}

	/**
	 *
	 * Skip terms not in the current query
	 */
	public function skip_terms() {

		$this->continue  = false;
		$additionalTerms = false;

		if ( isset( $_GET[ $this->getTerm ] ) ) :

			$additionalTerms = explode( ',', sanitize_text_field( $_GET[ $this->getTerm ] ) );

		endif;

		// Skip this term if it doesn't belong to any of the current entries, to avoid 404
		if ( ! in_array( $this->currentTerm->term_id, $this->currentTermsIDs ) ) {

			if ( is_array( $additionalTerms ) ) {

				if (
					! in_array( $this->currentTerm->term_id, $additionalTerms ) ||
					! in_array( $this->currentTerm->slug, $additionalTerms )
				) {

					$this->continue = true;
					return;

				}
			} elseif (
				(int) $this->currentTerm->term_id !== (int) $additionalTerms ||
				strval( $this->currentTerm->slug ) !== strval( $additionalTerms )
			) {

				$this->continue = true;
				return;

			} else {

				$this->continue = true;
				return;

			}
		}
	}

	/**
	 *
	 *  Skip Excluded Terms
	 */
	public function skip_excluded_terms() {

		$this->continue = false;

		if ( in_array( $this->currentTerm->term_id, $this->excludedTerms ) || in_array( $this->currentTerm->slug, $this->excludedTerms ) ) {
			$this->continue = true;
		}
	}

	/**
	 *
	 * Retrieves the active state of the link.
	 */
	public function get_active_state() {

		global $wp;
		$this->currentActiveState = '';

		// if we are on the main taxonomy, also check the url slugs for the active tag
		// Also use this to determine if a removal link is present or not, and to determine the base url for all other filters
		if ( $this->isMain && ! $this->forceNoMain ) :

			$currentPageSlug = add_query_arg( array(), $wp->request );
			$currentSlugs    = explode( '/', $currentPageSlug );

			if ( in_array( $this->currentTerm->slug, $currentSlugs ) ) {
				$this->currentActiveState = ' active';
			}

		endif;

		// Also search for the slug in the query string, to see if it's active
		if ( isset( $_GET[ $this->getTerm ] ) && $this->currentActiveState != ' active' ) :

			if ( $this->type === 'Post Meta' ) {
				$slug = urldecode( $this->currentTerm );
				$id   = urldecode( $this->currentTerm );
			} else {
				$slug = $this->currentTerm->slug;
				$id   = $this->currentTerm->term_id;
			}

			$search   = $this->getTerm . '=';
			$allAfter = substr( self::$currentUrl, strpos( self::$currentUrl, "$search" ) );
			$allAfter = str_replace( $search, '', $allAfter );

			$thisSlugs         = explode( '&', $allAfter, 2 )[0];
			$currentSlugsOrIDs = explode( ',', $thisSlugs );

			if ( in_array( $slug, $currentSlugsOrIDs ) || in_array( $id, $currentSlugsOrIDs ) ) {
				$this->currentActiveState = ' active';
			}

		endif;
	}

	public function get_checkbox() {

		if ( $this->isMultiFilter && ! $this->directChoice ) :

			$this->checkbox = $this->checkboxHTML;

			$this->removeIcon = '';

		endif;
	}

	/**
	 *
	 * Retrieves the link for the current item.
	 * If it's active, also pushes the link to the removal links array
	 */
	public function get_link() {

		( $this->type === 'Taxonomy' ) ? $this->removalFilterName = $this->currentTerm->name : $this->removalFilterName = $this->currentTerm;

		$this->removeIcon = '';

		if ( $this->isMain && ! $this->forceNoMain ) :

			// If it is a child term
			if ( $this->currentTerm->parent ) {

				// If's active, also get the removal icon and link
				if ( $this->currentActiveState === ' active' ) {

					$this->currentLink = get_category_link( $this->currentTerm->parent );
					$this->removeIcon  = $this->removeIconHTML;

					if ( $this->currentTerm->term_id === $this->currentQueriedObjectID ) {
						// array_push(self::$removalLinks, array($this->removalFilterName => $this->currentLink));
					} else {
						$this->get_removal_link();
						$this->currentLink = $this->removalLink;
						array_push( self::$removalLinks, array( $this->removalFilterName => $this->currentLink ) );
					}
					return;

				} else {

					$this->currentLink = get_category_link( $this->currentTerm->term_id );
					return;

				}

				// If it is NOT a child term
			} else {

				$this->currentLink = get_category_link( $this->currentTerm->term_id );
				return;

			}

		endif;

		// If's active, also get the removal icon and link
		if ( $this->currentActiveState === ' active' ) :

			$this->get_removal_link();
			$this->currentLink = $this->removalLink;
			$this->removeIcon  = $this->removeIconHTML;

			// Collect removal links
			array_push( self::$removalLinks, array( $this->removalFilterName => $this->currentLink ) );

			return;

		endif;

		$urlExploded = explode( '?', self::$currentUrl );
		$urlBefore   = $urlExploded[0];
		$urlParams   = array_key_exists( 1, $urlExploded ) ? explode( '&', $urlExploded[1] ) : array();

		$urlNewParams = array();
		$updated      = false;

		if ( $this->type === 'Post Meta' ) {
			$replace = $this->getTerm . '=' . urldecode( $this->currentTerm );
		} else {
			( $this->slugBased ) ? $replace = $this->getTerm . '=' . $this->currentTerm->slug : $replace = $this->getTerm . '=' . $this->currentTerm->term_id;
		}

		foreach ( $urlParams as $param ) {

			$paramExploded = explode( '=', $param );
			$paramTerm     = $paramExploded[0];

			// Update only the current param
			if ( $this->getTerm === $paramTerm ) {

				$urlNewParams[ self::$index[ $this->termType ][0] ] = $replace;

				$updated = true;

			} elseif ( array_key_exists( $paramTerm, self::$index ) ) { // If it's not the current param, just rebuild the url

				$urlNewParams[ self::$index[ $paramTerm ][0] ] = $param;

			}
		}

		// If the term wasn't in the query params of the url, add it
		if ( ! $updated ) {
			$urlNewParams[ self::$index[ $this->getTerm ][0] ] = $replace;
		}

		ksort( $urlNewParams, SORT_NUMERIC );

		$this->currentLink = $urlBefore . '/?' . implode( '&', $urlNewParams );
	}

	/**
	 *
	 * Gets the removal link.
	 */
	public function get_removal_link() {

		// Se è il current queried object o parente di questo, metti la url con senza slug, se non lo è, ricostruisci con get term

		$urlExploded = explode( '?', self::$currentUrl );
		$urlBefore   = $urlExploded[0];
		$urlParams   = explode( '&', $urlExploded[1] );

		$urlNewParams      = array();
		$updated           = false;
		$hasMultipleValues = false;

		// Rebuild the url
		foreach ( $urlParams as $param ) {

			$paramExploded = explode( '=', $param );
			$paramTerm     = $paramExploded[0];

			if ( array_key_exists( $paramTerm, self::$index ) && $paramTerm !== $this->getTerm ) {
				$urlNewParams[ self::$index[ $paramTerm ][0] ] = $param;
			}

			// if it's a multifilter with multiple values selected, replace the value;
			if ( $paramTerm === $this->getTerm ) {

				$values    = explode( ',', $paramExploded[1] );
				$newValues = array();

				if ( count( $values ) > 1 ) {
					$hasMultipleValues = true;

					foreach ( $values as $val ) {
						( $this->slugBased ) ? $curVal = $this->currentTerm->slug : $curVal = $this->currentTerm->term_id;

						if ( $val !== $curVal ) {

							/**
							 * Check if the terms is not the current one, AND that it is in the current query, to avoid 404.
							 * As an example, let's say you select 3 categories, then select an year, and only one of those categories
							 * is in that year. Than you don't want the other categories in the remove link, because togheter with that year
							 * you would get a 404.                          *
							 */
							( $this->slugBased ) ? $valTerm = get_term_by( 'slug', $val, $this->termType ) : $valTerm = get_term_by( 'id', $val, $this->termType );
							if ( in_array( $valTerm->term_id, $this->currentTermsIDs ) ) {
								array_push( $newValues, $val );
							}
						}
					}

					if ( count( $newValues ) > 0 ) {
						$urlNewParams[ self::$index[ $this->getTerm ][0] ] = $paramTerm . '=' . implode( ',', $newValues );
					} else {
						// Unset the param if it's empty
						unset( $urlNewParams[ self::$index[ $this->getTerm ][0] ] );
					}
				}
			}
		}

		// Completely remove the filter if it's not a multifilter with multiple values selected
		if ( ! $hasMultipleValues ) {
			unset( $urlNewParams[ self::$index[ $this->getTerm ][0] ] );
		}

		ksort( $urlNewParams, SORT_NUMERIC );

		switch ( count( $urlNewParams ) ) {

			case 0:
				$this->removalLink = $urlBefore . '/';
				break;

			default:
				$this->removalLink = $urlBefore . '/?' . implode( '&', $urlNewParams );
				break;

		}
	}

	/**
	 *
	 * Retrives data for multifilters javascript.
	 */
	public function get_multifilter_data() {

		if ( $this->isMultiFilter ) :

			if ( $this->slugBased ) {
				$this->currentTerm ? $value = $this->currentTerm->slug : $value = '';
			} else {
				$value = $this->currentTerm->term_id;
			}

			$this->multiFilterData = ' data-mfterm="' . $this->getTerm . '" data-mfvalue="' . $value . '" data-mfindex="' . self::$index[ $this->getTerm ][0] . '" ';
			array_push( $this->additionalUlClasses, 'archive-filters__ul--multifilter' );

		else :

			$this->multiFilterData = '';

		endif;
	}

	public function output_multifilter_button() {

		if ( $this->isMultiFilter ) :

			if ( ! $this->directChoice ) :

				?>
				<button class="archive-filters__multifilter-button disabled"><?php echo __( 'Filtra risultati', 'ap-wp-theme' ); ?></button>
				<?php

			endif;

		endif;
	}

	public function get_layout() {

		( $this->layout === 'vertical' ) ? $this->filterClass = ' archive-filters--vertical' : $this->filterClass = ' archive-filters--horizontal';
	}

	/**
	 *
	 * Gets all the child terms. Calls itself in the end for nested child terms.
	 */
	public function get_child_terms() {

		if ( $this->currentActiveState === ' active' ||
			! $this->isMain ||
			$this->forceNoMain ||
			$this->expandAllTerms ||
			// If it's a product_cat or category filter and we are in the shop or blog page, show all child terms
			( class_exists( 'WooCommerce' ) && is_shop() && $this->termType === 'product_cat' ) ||
			( is_home() && $this->termType === 'category' ) ) :

			$this->childTerms = get_terms(
				array(
					'taxonomy'   => $this->termType,
					'hide_empty' => true,
					'child_of'   => $this->currentTerm->term_id,
				)
			);

		else :

			$this->childTerms = array();

		endif;

		if ( ! empty( $this->childTerms ) ) :
			?>

			<ul class="archive-filters__ul--second-level">
				<?php

				foreach ( $this->childTerms as $this->currentTerm ) :

					$this->skip_terms();
					if ( $this->continue ) {
						continue;
					}

					$this->skip_excluded_terms();
					if ( $this->continue ) {
						continue;
					}

					$this->get_active_state();

					$this->get_link();

					$this->get_checkbox();

					$this->get_multifilter_data();

					?>
						<li class="archive-filters__li--second-level">

							<a <?php echo $this->multiFilterData; ?>class="archive-filters__link<?php echo $this->currentActiveState; ?>" href="<?php echo $this->currentLink; ?>" alt="<?php echo esc_attr( sprintf( __( 'View all products in %s', 'ap-wp-theme' ), $this->currentTerm->name ) ); ?>">
							<?php echo $this->checkbox . $this->currentTerm->name . $this->removeIcon; ?>
							</a>

						<?php $this->get_child_terms(); ?>

						</li>
						<?php

					endforeach;

				?>
			</ul>
			<?php

		endif;
	}

	/**
	 *
	 * Filter a taxonomy
	 */
	public function filter_taxonomy() {

		$this->hiddenClass = '';

		if ( $this->currentParent && $this->showTopParent ) :

			$this->get_current_term_top_parent();

		else :

			$this->get_all_current_terms();

		endif;

		// Control all filters based on the main taxonomy
		if ( ! $this->forceNoMain && is_tax( $this->termType ) &&
			! ( ( class_exists( 'WooCommerce' ) && is_shop() && $this->termType === 'product_tag' ) ) ||
			( class_exists( 'WooCommerce' ) && is_shop() && $this->termType === 'product_cat' && $this->termType !== 'product_tag' ) ||
			( is_home() && $this->termType === 'category' ) ) {

			$this->isMain = true;

		}

		// Avoid to use the product_cat query string in url because it would set $this->isMain to true even if it's not (is_tax() would return true)
		if ( $this->termType === 'category' ) {
			$this->getTerm = 'add_cat';
		}

		$this->get_multifilter_data();

		ob_start();
		?>

		<ul class="archive-filters__ul
		<?php
		if ( ! empty( $this->additionalUlClasses ) ) {
			echo ' ' . implode( ' ', $this->additionalUlClasses );}
		?>
		">
			<div class="archive-filters__back-button"><?php echo $this->arrowBack . __( 'Back', 'ap-wp-theme' ); ?></div>
			<?php

			$termsCount = 0;

			foreach ( $this->allTerms as $this->currentTerm ) :

				$this->hiddenClass = '';

				if ( ! $this->showAll ) :

					$this->skip_terms();
					if ( $this->continue ) {
						continue;
					}

				endif;

				$this->skip_excluded_terms();
				if ( $this->continue ) {
					continue;
				}

				if ( $this->skipSelf ) :

					if ( $this->currentTerm->term_id === $this->currentQueriedObjectID ) {
						$this->hiddenClass = ' archive-filters__link--hidden';
					}

				endif;

				++$termsCount;

				$this->get_active_state();

				$this->get_link();

				$this->get_checkbox();

				$this->get_multifilter_data();

				if ( $this->hiddenClass ) : // directly get child terms if skipSelf is set to true

					$this->get_child_terms();

				else :

					if ( $this->hideIfChildren ) {

						$hasTerms = get_terms(
							array(
								'taxonomy'   => $this->termType,
								'hide_empty' => true,
								'child_of'   => $this->currentTerm->term_id,
								'meta_query',
								array(
									array(
										'key'   => '_stock_status',
										'value' => 'instock',
									),
								),
							)
						);

						if ( ! empty( $hasTerms ) ) {
							array_push( $this->excludedTerms, $this->currentTerm->term_id );
							$this->get_child_terms();
							break;
						}
					}

					?>
					<li class="archive-filters__li">

						<a <?php echo $this->multiFilterData; ?>class="archive-filters__link<?php echo $this->currentActiveState . $this->hiddenClass; ?>" href="<?php echo $this->currentLink; ?>" alt="<?php echo esc_attr( sprintf( __( 'View all products in %s', 'ap-wp-theme' ), $this->currentTerm->name ) ); ?>">
							<?php echo $this->checkbox . $this->currentTerm->name . $this->removeIcon; ?>
						</a>

						<?php $this->get_child_terms(); ?>

					</li>
					<?php

				endif;

			endforeach;

			$this->output_multifilter_button();

			?>

		</ul>
		<?php

		$filters = ob_get_clean();

		$this->get_layout();

		if ( $termsCount > 0 ) :

			?>
			<div class="archive-filters<?php echo $this->filterClass; ?>">
				<?php
				echo '<h3 class="widgettitle widget-title">' . self::$index[ $this->getTerm ][2] . $this->arrowDown . '</h3>';
				echo $filters;
				?>
			</div>
			<?php

		endif;
	}

	/**
	 *
	 * Filter a field.
	 */
	public function filter_field() {

		$this->get_all_current_fields();

		$this->get_multifilter_data();

		if ( ! empty( $this->allTerms ) ) :

			$this->get_layout();

			?>
			<div class="archive-filters<?php echo $this->filterClass; ?>">

				<?php echo '<h3 class="widgettitle widget-title">' . self::$index[ $this->getTerm ][2] . $this->arrowDown . '</h3>'; ?>

				<ul class="archive-filters__ul">
					<div class="archive-filters__back-button"><?php echo $this->arrowBack . __( 'Back', 'ap-wp-theme' ); ?></div>
					<?php

					foreach ( $this->allTerms as $this->currentTerm ) :

						$this->get_active_state();

						$this->get_link();

						$this->get_checkbox();

						?>
						<li class="archive-filters__li">

							<a class="archive-filters__link<?php echo $this->currentActiveState; ?>" href="<?php echo $this->currentLink; ?>" alt="">
								<?php echo $this->checkbox . $this->currentTerm . $this->removeIcon; ?>
							</a>

						</li>
						<?php

					endforeach;

					$this->output_multifilter_button();

					?>
				</ul>

			</div>
			<?php

		endif;
	}

	/**
	 *
	 * Render the filter.
	 */
	public function render() {

		ob_start();

		echo '<div data-currenturl="' . self::$currentUrl . '" class="currenturldiv hidden"></div>';

		if ( array_key_exists( $this->getTerm, self::$index ) && self::$index[ $this->getTerm ][1] === 'Taxonomy' ) {

			$this->type = 'Taxonomy';
			$this->filter_taxonomy();

		} elseif ( array_key_exists( $this->getTerm, self::$index ) && self::$index[ $this->getTerm ][1] === 'Post Meta' ) {

			$this->type   = 'Post Meta';
			$this->isMain = false;
			$this->filter_field();

		} else {
			return;
		}

		return ob_get_clean();
	}
}
