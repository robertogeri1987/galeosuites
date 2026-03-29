<?php
/**
 * Flexible Archive Filters Pre Get Posts Filters
 *
 * @author Alessio Pangos
 */
namespace Classes\Core;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class FlexibleArchiveFiltersHelpers {

	public static function shortcode_functions() {

		add_shortcode(
			'customfilters',
			function ( $atts = array() ) {
				extract(
					shortcode_atts(
						array(
							'filter_name'     => '',
							'current_parent'  => false,
							'first_parent'    => false,
							'expand'          => false,
							'show_all'        => false,
							'force_no_main'   => false,
							'show_all_higher' => false,
							'skip_self'       => false,
							'excluded_terms'  => '',
							'show_top_parent' => false,
							'hide_parent'     => false,
							'forced_parent'   => false,
							'multifilter'     => false,
							'direct'          => false,
							'layout'          => 'vertical',
							'term_type'       => '',
							'get_term'        => '',
						),
						$atts
					)
				);

				$filter = new FlexibleArchiveFilters();

				$current_queried_object_id = get_queried_object_id();

				$filter->currentQueriedObjectID = $current_queried_object_id;

				if ( $term_type != '' ) {
					$filter->termType = $term_type;
				}
				if ( $get_term != '' ) {
					$filter->getTerm = $get_term;
				}
				if ( $filter_name != '' ) {
					$filter->filterName = $filter_name;
				}
				if ( $expand ) {
					$filter->expandAllTerms = true;
				}
				if ( $show_all ) {
					$filter->showAll = true;
				}
				if ( $force_no_main ) {
					$filter->forceNoMain = true;
				}
				if ( $show_all_higher ) {
					$filter->showAllHigherLevel = true;
				}
				if ( $show_top_parent ) {
					$filter->showTopParent = true;
				}
				if ( $skip_self ) {
					$filter->skipSelf = $current_queried_object_id;
				}
				if ( $hide_parent ) {
					$filter->hideParent = $current_queried_object_id;
				}
				if ( $first_parent ) {
					$filter->firstParent = true;
				}
				if ( $forced_parent ) {
					$filter->currentParent = $forced_parent;
				}
				$filter->excludedTerms = explode( ',', $excluded_terms );

				$filter->layout                              = $layout;
				$filter->isMultiFilter                       = $multifilter;
				$filter->directChoice                        = $direct;
				( $current_parent ) ? $filter->currentParent = $current_queried_object_id : $filter->currentParent = 0;

				/*
				introduci hide_parent
				introduci forced_parent (ad esempio tutti i figli di documentaries)
				introduci same_parent
				introduci skip_self per non far apparire il termine della query attuale tra i filtri
				introduci excluded_terms
				*/

				return $filter->render();
			}
		);
	}

	public static function pre_get_posts_filter() {

		add_action(
			'pre_get_posts',
			function ( $query ) {

				if ( ! is_admin() && $query->is_main_query() ) {

					if ( isset( $_GET['add_cat'] ) ) :

						$add_cat = explode( ',', sanitize_text_field( $_GET['add_cat'] ) );

						$query->set(
							'tax_query',
							array(
								array(
									'taxonomy' => 'category',
									'field'    => 'slug',
									'terms'    => $add_cat,
								),
							)
						);

					endif;

					if ( isset( $_GET['p_cat'] ) ) :

						$p_cat = explode( ',', sanitize_text_field( $_GET['p_cat'] ) );

						$query->set(
							'tax_query',
							array(
								'relation' => 'AND',
								array(
									'taxonomy' => 'product_cat',
									'field'    => 'slug',
									'terms'    => $p_cat,
								),
							)
						);

					endif;

					if ( have_rows( 'ap_wp_filtri_personalizzati', 'options' ) ) :

						while ( have_rows( 'ap_wp_filtri_personalizzati', 'options' ) ) :
							the_row();

							$type     = get_sub_field( 'ap_wp_filter_type' );
							$index    = get_sub_field( 'ap_wp_filter_index' );
							$queryVar = get_sub_field( 'ap_wp_filter_query_var' );
							$sysName  = get_sub_field( 'ap_wp_filter_system_name' );

							if ( isset( $_GET[ $queryVar ] ) ) :

								$params = explode( ',', sanitize_text_field( $_GET[ $queryVar ] ) );

								if ( $type === 'Taxonomy' ) {

									$query->set(
										'tax_query',
										array(
											'relation' => 'AND',
											array(
												'taxonomy' => $sysName,
												'field'    => 'slug',
												'terms'    => $params,
											),
										)
									);

								} else {

									$query->set(
										'meta_query',
										array(
											'relation' => 'AND',
											array(
												'key'     => $sysName,
												'value'   => $params,
												'compare' => 'IN',
											),
										)
									);

								}

							endif;

						endwhile;

					endif;

				}
			}
		);
	}
}
