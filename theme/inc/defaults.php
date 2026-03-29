<?php
/**
 * AP WP Theme
 *
 * This file adds the default settings to the AP WP Theme theme.
 *
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

add_filter( 'http_request_args', 'ap_wp_dont_update_theme', 5, 2 );
/**
 * Don't Update Theme.
 *
 * If there is a theme in the repo with the same name,
 * this prevents WP from prompting an update.
 *
 * @param  array  $request Request arguments.
 * @param  string $url     Request url.
 *
 * @return array  request arguments
 */
function ap_wp_dont_update_theme( $request, $url ) {

	// Not a theme update request. Bail immediately.
	if ( 0 !== strpos( $url, 'http://api.wordpress.org/themes/update-check' ) ) {

		return $request;

	}

	$themes = unserialize( $request['body']['themes'] );

	unset( $themes[ get_option( 'template' ) ] );
	unset( $themes[ get_option( 'stylesheet' ) ] );

	$request['body']['themes'] = serialize( $themes );

	return $request;

}

add_filter( 'simple_social_default_styles', 'ap_wp_theme_social_default_styles' );
/**
 * Theme Simple Social Icon defaults.
 *
 * @since 1.0.0
 *
 * @param  array $defaults Default Simple Social Icons settings.
 * @return array Custom settings.
 */
function ap_wp_theme_social_default_styles( $defaults ) {

	$args = array(
		'alignment'              => 'alignleft',
		'background_color'       => '#333333',
		'background_color_hover' => '#ffffff',
		'border_radius'          => 36,
		'border_color'           => '#ffffff',
		'border_color_hover'     => '#ffffff',
		'border_width'           => 0,
		'icon_color'             => '#ffffff',
		'icon_color_hover'       => '#333333',
		'size'                   => 36,
		'new_window'             => 1,
		'facebook'               => '#',
		'twitter'                => '#',
	);

	$args = wp_parse_args( $args, $defaults );

	return $args;

}

add_filter( 'pt-ocdi/import_files', 'ap_wp_theme_demo_import' );
/**
 * One click demo import settings.
 *
 * @since  0.1.0
 *
 * @return array
 */
function ap_wp_theme_demo_import() {

	return array(
		array(
			'local_import_file'        => get_stylesheet_directory() . '/sample.xml',
			'local_import_widget_file' => get_stylesheet_directory() . '/widgets.wie',
			// 'local_import_customizer_file' => get_stylesheet_directory() . '/customizer.dat',
			'import_file_name'         => 'Demo Import',
			'categories'               => false,
			'local_import_redux'       => false,
			'import_preview_image_url' => false,
			'import_notice'            => false,
		),
	);

}

add_filter( 'pt-ocdi/after_all_import_execution', 'ap_wp_theme_after_demo_import', 999 );
/**
 * Set default pages after demo import.
 *
 * Automatically creates and sets the Static Front Page and the Page for Posts
 * upon theme activation, only if these pages don't already exist and only
 * if the site does not already display a static page on the homepage.
 *
 * @return void
 */
function ap_wp_theme_after_demo_import() {

	// Assign front page and posts page (blog page).
	$home = get_page_by_title( 'Home' );
	$blog = get_page_by_title( 'Blog' );

	if ( $home && $blog ) {

		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $home->ID );
		update_option( 'page_for_posts', $blog->ID );

	}

	// Set the WooCommerce shop page.
	$shop = get_page_by_title( 'Shop' );
	if ( $shop ) {

		update_option( 'woocommerce_shop_page_id', $shop->ID );

	}

	// Trash "Hello World" post.
	wp_delete_post( 1 );

	// Update permalink structure.
	global $wp_rewrite;
	$wp_rewrite->set_permalink_structure( '/%postname%/' );
	$wp_rewrite->flush_rules();

}
