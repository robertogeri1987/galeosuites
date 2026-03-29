<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 *
 *  ACF Pro Options Pages
 *
 *  @author Alessio Pangos
 */

if ( function_exists( 'acf_add_options_page' ) ) {

	acf_add_options_page(
		array(
			'page_title' => 'Impostazioni Tema',
			'menu_title' => 'Impostazioni Tema',
			'menu_slug'  => 'impostazioni-sezioni-tema',
			'capability' => 'manage_options',
			'redirect'   => false,
			'position'   => 70,
		)
	);

}

/**
 *
 *  ACF Pro Options Page for the Mega Menu
 *
 *  @author Alessio Pangos
 */

if ( function_exists( 'acf_add_options_page' ) ) {

	acf_add_options_page(
		array(
			'page_title' => 'Mega Menu',
			'menu_title' => 'Mega Menu',
			'menu_slug'  => 'mega-menu-settings',
			'capability' => 'manage_options',
			'redirect'   => false,
			'icon_url'   => 'dashicons-menu',
			'position'   => 60,
		)
	);

}

/**
 *
 *  ACF Pro Options Page for Flexible Popup
 *
 *  @author Alessio Pangos
 */

if ( function_exists( 'acf_add_options_page' ) ) {

	acf_add_options_page(
		array(
			'page_title' => 'Popup',
			'menu_title' => 'Popup',
			'menu_slug'  => 'popup-settings',
			'capability' => 'edit_posts',
			'redirect'   => false,
			'icon_url'   => 'dashicons-cover-image',
			'position'   => 71,
		)
	);

}

/**
 *
 *  ACF Pro Options Page for Flexible Filters
 *
 *  @author Alessio Pangos
 */

if ( FLEX_FILTERS_ENABLED ) :

	if ( function_exists( 'acf_add_options_page' ) ) {

		acf_add_options_page(
			array(
				'page_title' => 'Filters',
				'menu_title' => 'Filters',
				'menu_slug'  => 'filters-settings',
				'capability' => 'manage_options',
				'redirect'   => false,
				'icon_url'   => 'dashicons-forms',
				'position'   => 72,
			)
		);

	}

endif;
