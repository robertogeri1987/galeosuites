<?php
/**
 * AP WP Theme
 *
 * This file adds footer and sidebar widgets to the AP WP Theme theme.
 *
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Register widget areas
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function ap_wp_theme_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'ap-wp-theme' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'ap-wp-theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widgettitle widget-title">',
			'after_title'   => '</h3>',
		)
	);
	// register_sidebar(
	// array(
	// 'name'          => esc_html__( 'Horizontal Sidebar', 'ap-wp-theme' ),
	// 'id'            => 'sidebar-2',
	// 'description'   => esc_html__( 'Add widgets here.', 'ap-wp-theme' ),
	// 'before_widget' => '<div class="archive-filters__col"><section id="%1$s" class="widget %2$s">',
	// 'after_widget'  => '</section></div>',
	// 'before_title'  => '<h3 class="widgettitle widget-title">',
	// 'after_title'   => '</h3>',
	// )
	// );
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer 1', 'ap-wp-theme' ),
			'id'            => 'footer-1',
			'description'   => esc_html__( 'Add widgets here.', 'ap-wp-theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widgettitle widget-title">',
			'after_title'   => '</h3>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer 2', 'ap-wp-theme' ),
			'id'            => 'footer-2',
			'description'   => esc_html__( 'Add widgets here.', 'ap-wp-theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widgettitle widget-title">',
			'after_title'   => '</h3>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer 3', 'ap-wp-theme' ),
			'id'            => 'footer-3',
			'description'   => esc_html__( 'Add widgets here.', 'ap-wp-theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widgettitle widget-title">',
			'after_title'   => '</h3>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer 4', 'ap-wp-theme' ),
			'id'            => 'footer-4',
			'description'   => esc_html__( 'Add widgets here.', 'ap-wp-theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widgettitle widget-title">',
			'after_title'   => '</h3>',
		)
	);
}
add_action( 'widgets_init', 'ap_wp_theme_widgets_init' );
