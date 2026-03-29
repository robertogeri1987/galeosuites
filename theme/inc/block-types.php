<?php

$APWPThemeTWCustomBlocks = array(
	'core/block',
	// 'core/html',
	// 'core/embed',
	'core/shortcode',
	'acf/background-image-text',
	'acf/blocco-schede',
	'acf/carousel-slider',
	'acf/colonna-immagini',
	'acf/contact-form',
	'acf/cta',
	'acf/degustazione',
	'acf/etichette',
	'acf/exp-blocks',
	'acf/heading-hero',
	'acf/icone-camere',
	'acf/immagine-tre-colonne',
	'acf/image',
	'acf/image-text',
	'acf/image-text-two-columns',
	'acf/link-tre-colonne',
	'acf/lista-icone',
	'acf/location',
	'acf/mappa-arno',
	'acf/mappa-territorio',
	'acf/media-text-slider',
	'acf/person-slider',
	'acf/posts-selection',
	'acf/posts-slider',
	'acf/premi',
	'acf/products-selection',
	'acf/slider-full',
	'acf/testo-centrato',
	'acf/testo-colonne-immagine',
	'acf/testo-tre-colonne',
	'acf/testo-with-images',
	'acf/text',
	'acf/timeline',
	'acf/title',
	'acf/titolo-testo-immagine',
	'acf/video-plyr',
);

add_action( 'init', 'register_acf_blocks' );
function register_acf_blocks() {
	global $APWPThemeTWCustomBlocks;

	foreach ( $APWPThemeTWCustomBlocks as $block ) {
		$name = explode( '/', $block );
		if ( $name[0] === 'acf' ) {
			register_block_type( get_stylesheet_directory() . '/blocks/' . $name[1] );
		}
	}
}
function ap_wp_allowed_block_types( $block_editor_context, $editor_context ) {
	global $APWPThemeTWCustomBlocks;

	$allBlocks = array(
		'core/archives',
		'core/audio',
		'core/buttons',
		'core/categories',
		'core/code',
		'core/column',
		'core/columns',
		'core/coverImage',
		'core/embed',
		'core/file',
		'core/freeform',
		'core/gallery',
		'core/heading',
		'core/html',
		'core/image',
		'core/latestComments',
		'core/latestPosts',
		'core/list',
		'core/list-item',
		'core/more',
		'core/nextpage',
		'core/paragraph',
		'core/preformatted',
		'core/pullquote',
		'core/quote',
		'core/block',
		'core/separator',
		'core/shortcode',
		'core/spacer',
		'core/subhead',
		'core/table',
		'core/textColumns',
		'core/verse',
		'core/video',
		'core/html',
	);

	$merged = array_merge( $allBlocks, $APWPThemeTWCustomBlocks );

	if ( ! empty( $editor_context->post ) && 'post' !== $editor_context->post->post_type ) {
		return $APWPThemeTWCustomBlocks;
	}

	// Per post type specifico
	if ( ! empty( $editor_context->post ) && 'post' === $editor_context->post->post_type ) {
		return $merged;
	}

	return $block_editor_context;
}

add_filter( 'allowed_block_types_all', 'ap_wp_allowed_block_types', 10, 2 );
