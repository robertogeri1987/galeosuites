<?php
/**
 * Regiters the predictive search scripts and rest route
 *
 * @author Alessio Pangos
 */
namespace Classes\Core;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class PredictiveSearch {

	protected static $Initialized = false;

	public function __construct() {

		if ( ! self::$Initialized ) {
			self::Initialize();
		}
	}

	public static function EnqueueScripts() {

		wp_localize_script(
			'ap-wp-theme-tw-script',
			'searchParams',
			array(
				'root_url'               => site_url(),
				'noResultsMessage'       => __( 'La ricerca non ha prodotto nessun risultato', 'ap_wp_theme' ),
				'generalInfoTitle'       => __( 'Articoli e Pagine', 'ap_wp_theme' ),
				'productsTitle'          => __( 'Prodotti', 'ap_wp_theme' ),
				'productCategoriesTitle' => __( 'Categorie Prodotto', 'ap_wp_theme' ),
				'loader'                 => '<img data-predictive-search-loader class="max-w-[40px] mx-gap" src="' . get_stylesheet_directory_uri() . '/assets/images/search-loader.gif" alt="loading"/>',
				'nonce'                  => wp_create_nonce( 'predictive-search' ),
			)
		);
	}

	public static function SearchResults( $data ) {

		$args = array(
			'post_type'      => array( 'post', 'page', 'product' ),
			's'              => sanitize_text_field( $data['term'] ),
			'posts_per_page' => '10',
		);

		// Use the Relevanssi plugin to handle results, if enabled
		if ( function_exists( 'relevanssi_do_query' ) ) {

			$mainQuery = new \WP_Query();
			$mainQuery->parse_query( $args );

			relevanssi_do_query( $mainQuery );

		} else {

			$mainQuery = new \WP_Query( $args );

		}

		$results = array(
			'generalInfo'       => array(),
			'products'          => array(),
			'productCategories' => array(),
		);

		while ( $mainQuery->have_posts() ) {
			$mainQuery->the_post();

			if ( get_post_type() == 'post' || get_post_type() == 'page' ) {
				$url = get_the_post_thumbnail_url( 0, 'thumbnail' );
				array_push(
					$results['generalInfo'],
					array(
						'title'     => get_the_title(),
						'permalink' => get_the_permalink(),
						'postType'  => get_post_type(),
						'image'     => $url ? '<img class="searchform__results-image w-[40px] mr-hgap" alt="' . get_the_title() . '" src="' . $url . '" />' : '',
					)
				);
			}

			if ( get_post_type() == 'product' ) {
				$url = get_the_post_thumbnail_url( 0, 'woocommerce_gallery_thumbnail' );
				array_push(
					$results['products'],
					array(
						'title'     => get_the_title(),
						'permalink' => get_the_permalink(),
						'image'     => $url ? '<img class="searchform__results-image w-[40px] mr-gap" alt="' . get_the_title() . '" src="' . $url . '" />' : '',
					)
				);
			}
		}

		$productCategories = get_terms(
			array(
				'taxonomy'   => 'product_cat',
				'hide_empty' => true,
				'name__like' => sanitize_text_field( $data['term'] ),
			)
		);

		foreach ( $productCategories as $cat ) {
			$thumb_id = get_term_meta( $cat->term_id, 'thumbnail_id', true );
			$url      = wp_get_attachment_url( $thumb_id, 'woocommerce_gallery_thumbnail' );

			array_push(
				$results['productCategories'],
				array(
					'title'     => $cat->name,
					'permalink' => get_term_link( $cat->term_id, 'product_cat' ),
					'image'     => $url ? '<img class="searchform__results-image w-[40px] mr-gap" alt="' . $cat->name . '" src="' . $url . '" />' : '',
				)
			);
		}

		wp_reset_postdata();

		return $results;
	}

	public static function RestRouteInit() {

		register_rest_route(
			'searchroute/v1',
			'search',
			array(
				'methods'             => \WP_REST_SERVER::READABLE,
				'callback'            => '\Classes\Core\PredictiveSearch::SearchResults',
				'permission_callback' => '__return_true',
			)
		);
	}

	protected static function Initialize() {

		add_action( 'wp_enqueue_scripts', '\Classes\Core\PredictiveSearch::EnqueueScripts' );
		add_action( 'rest_api_init', '\Classes\Core\PredictiveSearch::RestRouteInit' );
		self::$Initialized = true;
	}
}
