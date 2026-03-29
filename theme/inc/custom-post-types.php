<?php
/**
 * Symcro Custom Post Types
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/* Register new post type */
add_action( 'init', 'ap_wp_symcro_cpt' );
function ap_wp_symcro_cpt() {

	// Recensione Post type
	register_post_type(
		'camere',
		array(
			'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			'public'             => true,
			'has_archive'        => true,
			'rewrite'            => array('slug' => __('camere', 'ap-wp-theme')),
			'show_in_rest'       => true,
			'labels'             => array(
				'name'          => __( 'Camere', 'ap-wp-theme' ),
				'add_new_item'  => __( 'Aggiungi nuova camera', 'ap-wp-theme' ),
				'add_new'       => __( 'Aggiungi nuova camera', 'ap-wp-theme' ),
				'edit_item'     => __( 'Modifica Camere', 'ap-wp-theme' ),
				'all_items'     => __( 'Tutte le Camere', 'ap-wp-theme' ),
				'singular_name' => __( 'Camere', 'ap-wp-theme' ),
			),
			'menu_icon'          => 'dashicons-star-filled',
		)
	);

	// Register a tag taxonomy for portfolio
	register_taxonomy(
		'categoria_camere',
		'camere',
		array(
			'labels'       => array(
				'name'          => __('Categorie Camere', 'ap-wp-theme'),
				'singular_name' => __('Categorie Camere', 'ap-wp-theme'),
			),
			'public'       => true,
			'show_in_rest' => true,
			'hierarchical' => true,
		)
	);
}

