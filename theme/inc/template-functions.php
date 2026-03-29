<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package ap-wp-theme-tw
 */

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function ap_wp_theme_tw_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'ap_wp_theme_tw_pingback_header' );

/**
 * Changes comment form default fields.
 *
 * @param array $defaults The default comment form arguments.
 *
 * @return array Returns the modified fields.
 */
function ap_wp_theme_tw_comment_form_defaults( $defaults ) {
	$comment_field = $defaults['comment_field'];

	// Adjust height of comment form.
	$defaults['comment_field'] = preg_replace( '/rows="\d+"/', 'rows="5"', $comment_field );

	return $defaults;
}
add_filter( 'comment_form_defaults', 'ap_wp_theme_tw_comment_form_defaults' );

/**
 * Filters the default archive titles.
 */
function ap_wp_theme_tw_get_the_archive_title() {
	if ( is_category() ) {
		$title = __( 'Category Archives: ', 'ap-wp-theme-tw' ) . '<span>' . single_term_title( '', false ) . '</span>';
	} elseif ( is_tag() ) {
		$title = __( 'Tag Archives: ', 'ap-wp-theme-tw' ) . '<span>' . single_term_title( '', false ) . '</span>';
	} elseif ( is_author() ) {
		$title = __( 'Author Archives: ', 'ap-wp-theme-tw' ) . '<span>' . get_the_author_meta( 'display_name' ) . '</span>';
	} elseif ( is_year() ) {
		$title = __( 'Yearly Archives: ', 'ap-wp-theme-tw' ) . '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'ap-wp-theme-tw' ) ) . '</span>';
	} elseif ( is_month() ) {
		$title = __( 'Monthly Archives: ', 'ap-wp-theme-tw' ) . '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'ap-wp-theme-tw' ) ) . '</span>';
	} elseif ( is_day() ) {
		$title = __( 'Daily Archives: ', 'ap-wp-theme-tw' ) . '<span>' . get_the_date() . '</span>';
	} elseif ( is_post_type_archive() ) {
		$cpt   = get_post_type_object( get_queried_object()->name );
		$title = sprintf(
			/* translators: %s: Post type singular name */
			esc_html__( '%s Archives', 'ap-wp-theme-tw' ),
			$cpt->labels->singular_name
		);
	} elseif ( is_tax() ) {
		$tax   = get_taxonomy( get_queried_object()->taxonomy );
		$title = sprintf(
			/* translators: %s: Taxonomy singular name */
			esc_html__( '%s Archives', 'ap-wp-theme-tw' ),
			$tax->labels->singular_name
		);
	} else {
		$title = __( 'Archives:', 'ap-wp-theme-tw' );
	}
	return $title;
}
add_filter( 'get_the_archive_title', 'ap_wp_theme_tw_get_the_archive_title' );

/**
 * Determines if post thumbnail can be displayed.
 */
function ap_wp_theme_tw_can_show_post_thumbnail() {
	return apply_filters( 'ap_wp_theme_tw_can_show_post_thumbnail', ! post_password_required() && ! is_attachment() && has_post_thumbnail() );
}

/**
 * Returns the size for avatars used in the theme.
 */
function ap_wp_theme_tw_get_avatar_size() {
	return 60;
}

/**
 * Create the continue reading link
 */
function ap_wp_theme_tw_continue_reading_link() {

	if ( ! is_admin() ) {
		$continue_reading = sprintf(
			/* translators: %s: Name of current post. */
			wp_kses( __( 'Continue reading %s', 'ap-wp-theme-tw' ), array( 'span' => array( 'class' => array() ) ) ),
			the_title( '<span class="sr-only">"', '"</span>', false )
		);

		return '<a href="' . esc_url( get_permalink() ) . '">' . $continue_reading . '</a>';
	}
}

// Filter the excerpt more link.
add_filter( 'excerpt_more', 'ap_wp_theme_tw_continue_reading_link' );

// Filter the content more link.
add_filter( 'the_content_more_link', 'ap_wp_theme_tw_continue_reading_link' );

/**
 * Numeric pagination, inspired by the Genesis theme,
 * from https://www.wpbeginner.com/wp-themes/how-to-add-numeric-pagination-in-your-wordpress-theme/
 */
function ap_wp_numeric_posts_nav() {

	if ( is_singular() ) {
		return;
	}

	global $wp_query;

	/** Stop execution if there's only 1 page */
	if ( $wp_query->max_num_pages <= 1 ) {
		return;
	}

	$paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
	$max   = intval( $wp_query->max_num_pages );

	/** Add current page to the array */
	if ( $paged >= 1 ) {
		$links[] = $paged;
	}

	/** Add the pages around the current page to the array */
	if ( $paged >= 3 ) {
		$links[] = $paged - 1;
		$links[] = $paged - 2;
	}

	if ( ( $paged + 2 ) <= $max ) {
		$links[] = $paged + 2;
		$links[] = $paged + 1;
	}

	echo '<div class="pagination pagination--archive mt-gap flex items-center justify-center" role="navigation" aria-label="Pagination"><ul class="grid grid-flow-col auto-cols-max gap-gap mx-auto">' . "\n";

	/** Previous Post Link */
	if ( get_previous_posts_link() ) {
		printf( '<li class="icon-button icon-button--autow">%s</li>' . "\n", get_previous_posts_link() );
	}

	/** Link to first page, plus ellipses if necessary */
	if ( ! in_array( 1, $links ) ) {
		$class = 1 == $paged ? ' class="icon-button icon-button--autow"' : ' class="h-full flex items-center"';

		printf( '<li><a%s href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( 1 ) ), '1' );

		if ( ! in_array( 2, $links ) ) {
			echo '<li>…</li>';
		}
	}

	/** Link to current page, plus 2 pages in either direction if necessary */
	sort( $links );
	foreach ( (array) $links as $link ) {
		$class = $paged == $link ? ' class="flex items-center h-full text-primary pointer-events-none"' : ' class="h-full flex items-center"';
		printf( '<li><a%s href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $link ) ), $link );
	}

	/** Link to last page, plus ellipses if necessary */
	if ( ! in_array( $max, $links ) ) {
		if ( ! in_array( $max - 1, $links ) ) {
			echo '<li>…</li>' . "\n";
		}

		$class = $paged == $max ? ' class="flex items-center h-full"' : ' class="flex items-center h-full"';
		printf( '<li><a%s href="%s">%s</a></Eli>' . "\n", $class, esc_url( get_pagenum_link( $max ) ), $max );
	}

	/** Next Post Link */
	if ( get_next_posts_link() ) {
		printf( '<li class="icon-button icon-button--autow">%s</li>' . "\n", get_next_posts_link() );
	}

	echo '</ul></div>' . "\n";
}
