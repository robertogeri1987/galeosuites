<?php
/**
 * ap-wp-theme-tw functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package ap-wp-theme-tw
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! defined( 'GLOBAL_THEME_VERSION' ) ) {
	// Define theme constants.
	define( 'GLOBAL_THEME_NAME', 'AP WP Theme' );
	define( 'GLOBAL_THEME_VERSION', '1.0.2022-12-06-a39' );
}

// Enable predictive search functionality
define( 'PREDICTIVE_SEARCH_ENABLED', true );

// Enable Wishlist functionality
define( 'WISHLIST_FUNCTIONALITY_ENABLED', false );

// Get Filters Enabled Value
define( 'FLEX_FILTERS_ENABLED', class_exists( 'WooCommerce' ) ? true : false );

// Load block types
require_once get_stylesheet_directory() . '/inc/block-types.php';

if ( ! function_exists( 'ap_wp_theme_tw_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function ap_wp_theme_tw_setup() {

		// Make theme available for translation.
		load_theme_textdomain( 'ap-wp-theme', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		// Let WordPress manage the document title.
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Enable support for custom header image or video.
		add_theme_support(
			'custom-header',
			array(
				'header-selector'    => 'false',
				'default_image'      => get_stylesheet_directory_uri() . '/assets/images/hero.jpg',
				'header-text'        => true,
				'default-text-color' => 'ffffff',
				'width'              => 1920,
				'height'             => 1080,
				'flex-height'        => true,
				'flex-width'         => true,
				'uploads'            => true,
				'video'              => true,
				'jquery',
				'wp-head-callback'   => 'ap_wp_theme_custom_header',
			)
		);

		// Enable support for WooCommerce.
		add_theme_support( 'woocommerce' );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Add support for editor styles.
		add_theme_support( 'editor-styles' );

		// Enable shortcodes in HTML widgets.
		add_filter( 'widget_text', 'do_shortcode' );

		// Enqueue editor styles.
		// add_editor_style( 'style-editor.css' );

		// Set hero image size.
		add_image_size( 'hero', 1920, 1080, true );

		add_image_size( 'related', 640, 310, true );

		// Add support for responsive embedded content.
		add_theme_support( 'responsive-embeds' );

		// Enable support for page excerpts.
		add_post_type_support( 'page', 'excerpt' );

		// Remove support for block templates.
		remove_theme_support( 'block-templates' );

		// Gutenberg alignwide support
		add_theme_support( 'align-wide' );
	}
endif;
add_action( 'after_setup_theme', 'ap_wp_theme_tw_setup' );

// Hide admin bar for non admins
add_action( 'after_setup_theme', 'ap_hide_admin_bar' );
function ap_hide_admin_bar() {

	if ( ! current_user_can( 'edit_posts' ) ) {
		add_filter( 'show_admin_bar', '__return_false', PHP_INT_MAX );
	}
}

/**
 * Enqueue scripts and styles.
 */
function ap_wp_theme_tw_scripts() {
	wp_enqueue_style( 'ap-wp-theme-tw-style', get_stylesheet_uri(), array(), GLOBAL_THEME_VERSION );

	wp_dequeue_style( 'simple-social-icons-font' );

	// Enqueue JS hoverintent
	wp_deregister_script( 'hoverIntent' );

	/**
	 * Webpack bundle
	 */
	if ( preg_match( '/(Trident\/(\d{2,}|7|8|9)(.*)rv:(\d{2,}))|(MSIE\ (\d{2,}|8|9)(.*)Tablet\ PC)|(Trident\/(\d{2,}|7|8|9))/', $_SERVER['HTTP_USER_AGENT'], $match ) != 0 ) {
		// IE 11 bundle
		wp_enqueue_script( 'ap-wp-theme-tw-script', get_template_directory_uri() . '/js/legacy/scripts-blundled-legacy.js', array( 'jquery' ), GLOBAL_THEME_VERSION, true );
	} else {
		// Production modern browsers bundle
		wp_enqueue_script( 'ap-wp-theme-tw-script', get_template_directory_uri() . '/js/script.min.js', array( 'jquery' ), GLOBAL_THEME_VERSION, true );
	}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// Enqueue Google fonts.
	// wp_enqueue_style( 'ap-wp-google-font-fragment', 'https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,400;0,500;0,700;1,400&display=swap', array(), GLOBAL_THEME_VERSION );

	wp_localize_script(
		'ap-wp-theme-tw-script',
		'ajaxParams',
		array()
	);
}
add_action( 'wp_enqueue_scripts', 'ap_wp_theme_tw_scripts' );

function wpdocs_selectively_enqueue_admin_script( $hook ) {

	// wp_enqueue_style( 'ap-wp-google-font-fragment', 'https:// fonts.googleapis.com/css2?family=Inter:ital,wght@0,400;0,500;0,700;1,400&display=swap', array(), GLOBAL_THEME_VERSION );

	// Production modern browsers bundle
	wp_enqueue_script( 'ap-wp-theme-tw-script', get_template_directory_uri() . '/js/script.min.js', array( 'jquery' ), GLOBAL_THEME_VERSION, true );

	wp_localize_script(
		'ap-wp-theme-tw-script',
		'ajaxParams',
		array()
	);
}
add_action( 'enqueue_block_editor_assets', 'wpdocs_selectively_enqueue_admin_script' );

function ap_wp_theme_admin_styles() {
	global $pagenow;
	if ( in_array( $pagenow, array( 'post.php', 'post-new.php' ) ) && in_array( get_post_type(), array( 'post', 'page' ) ) ) {
		wp_enqueue_style( 'ap-wp-theme-tw-style-editor', get_stylesheet_directory_uri() . '/style-editor.css', array(), GLOBAL_THEME_VERSION );
	}
}
// add_action( 'admin_enqueue_scripts', 'ap_wp_theme_admin_styles' );

/**
 * Remove WordPress wpautop so it stops adding p and br tags everywhere
 */
function ap_wp_remove_the_wpautop_function() {
	remove_filter( 'the_content', 'wpautop' );
	// remove_filter( 'the_excerpt', 'wpautop' );
}

add_action( 'after_setup_theme', 'ap_wp_remove_the_wpautop_function' );

/**
 * Google fonts preconnect
 */
function ap_wp_google_font_loader_tag_filter( $html, $handle ) {
	if ( $handle === 'ap-wp-google-font-fragment' ) {
		$rel_preconnect = "rel='stylesheet preconnect'";

		return str_replace(
			"rel='stylesheet'",
			$rel_preconnect,
			$html
		);
	}
		return $html;
}
// add_filter( 'style_loader_tag', 'ap_wp_google_font_loader_tag_filter', 10, 2 );

/**
 * Classes Auto Loader
 *
 * @author Alessio Pangos
 * Auto loads classes
 */
spl_autoload_register(
	function ( $className ) {

		$class     = str_replace( '\\', '/', $className );
		$classPath = get_stylesheet_directory() . '/' . $class . '.php';

		if ( file_exists( $classPath ) ) {
			include_once $classPath;
		}
	}
);

/**
 * Add the block editor class to TinyMCE.
 *
 * This allows TinyMCE to use Tailwind Typography styles.
 *
 * @param array $settings TinyMCE settings.
 * @return array
 */
function ap_wp_theme_tw_tinymce_add_class( $settings ) {
	$settings['body_class'] = 'block-editor-block-list__layout';
	return $settings;
}
add_filter( 'tiny_mce_before_init', 'ap_wp_theme_tw_tinymce_add_class' );

// Custom template tags for this theme.
require_once get_stylesheet_directory() . '/inc/widgets.php';

// Custom template tags for this theme.
require_once get_stylesheet_directory() . '/inc/template-tags.php';

// Functions which enhance the theme by hooking into WordPress.
require_once get_stylesheet_directory() . '/inc/template-functions.php';

// Load page header functions.
require_once get_stylesheet_directory() . '/inc/page-header.php';

// Load options pages
require_once get_stylesheet_directory() . '/inc/acf-options-pages.php';

// Load default settings for the theme.
require_once get_stylesheet_directory() . '/inc/defaults.php';

// Load theme's recommended plugins.
require_once get_stylesheet_directory() . '/inc/plugins.php';

// Load theme's recommended custom post type.
require_once get_stylesheet_directory() . '/inc/custom-post-types.php';

// Load Woocommerce functions.
if ( class_exists( 'WooCommerce' ) ) {
	include_once get_stylesheet_directory() . '/functions-woocommerce.php';
}

if ( PREDICTIVE_SEARCH_ENABLED ) {
	$predictiveSearch = new \Classes\Core\PredictiveSearch();
}

// Load SVG icons
add_action( 'wp_footer', 'ap_svg_inline', 99 );
add_action( 'admin_footer', 'ap_svg_inline', 99 );
function ap_svg_inline() {

	echo '<div class="hidden">';
	echo file_get_contents( get_stylesheet_directory() . '/assets/svg/main.svg' );
	echo '</div>';
}

// Helper function to output SVGs
function ap_svg( $svgname, $svgtitle = '', $svgclass = null, $returnValue = null, $additionalAttributes = '' ) {

	if ( $svgclass === null ) {
		$svgclass = $svgname;
	}
	if ( $returnValue ) {
		return '<svg class="' . $svgclass . '"><use xlink:href="#' . $svgname . '"><title>' . $svgtitle . '</title></use></svg>';
	}
	?>
	<svg class="<?php echo $svgclass; ?>"<?php echo $additionalAttributes; ?>><title><?php echo $svgtitle; ?></title><use xlink:href="#<?php echo $svgname; ?>"></use></svg>
	<?php
}


/**
 * Returns a fake svg src to be used with lazyload, to avoid layout shifting
 *
 * @param string $val1 firts value of aspect ratio (i.e. 16/9 or 3/2 - default)
 * @param string $val2 second value of aspect ratio
 *
 * @return string the src for the lazyloaded image
 */
function ap_svg_src( $val1 = null, $val2 = null ) {
	if ( ! $val1 ) {
		$val1 = '3';
	}
	if ( ! $val2 ) {
		$val2 = '2';
	}
	ob_start();
	?>
	src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 <?php echo $val1; ?> <?php echo $val2; ?>'%3E%3C/svg%3E"
	<?php
	return ob_get_clean();
}

/**
 * Echoes @2x srcset images if found and the Perfect Images plugin is installed
 *
 * @param String $src
 */
function ap_wp_the_retina_srcset( $src = '' ) {
	if ( function_exists( 'wr2x_get_retina_from_url' ) ) {
		$retUrl = wr2x_get_retina_from_url( $src );
		if ( $retUrl ) {
			echo 'srcset="' . $src . ' 1x, ' . wr2x_get_retina_from_url( $src ) . ' 2x' . '" ';
		}
	}
}
/**
 * Returns @2x srcset images if found and the Perfect Images plugin is installed
 *
 * @param String $src
 */
function ap_wp_get_retina_srcset( $src = '' ) {
	if ( function_exists( 'wr2x_get_retina_from_url' ) ) {
		$retUrl = wr2x_get_retina_from_url( $src );
		if ( $retUrl ) {
			return 'srcset="' . $src . ' 1x, ' . wr2x_get_retina_from_url( $src ) . ' 2x' . '" ';
		}
	}
}

/**
 * Yoast SEO Breadcrumbs
 */
// add_action( 'ap_wp_before_entry_content', 'ap_wp_yoast_breadcrumbs' );
function ap_wp_yoast_breadcrumbs() {

	if ( function_exists( 'yoast_breadcrumb' ) ) {
		yoast_breadcrumb( '<div id="breadcrumbs" class="breadcrumb layout-container mx-auto">', '</div>' );
	}

	if ( function_exists( 'bcn_display' ) ) { // If Breadcrumbs NavXT is used instead of Yoast
		echo '<div id="breadcrumbs" class="breadcrumb layout-container mx-auto">';
		bcn_display();
		echo '</div>';
	}
}

// Scroll to top
add_action( 'wp_footer', 'ap_scroll_to_top' );
function ap_scroll_to_top() {

	?>
	<a href="#" class="footer__back-to-top fixed bottom-hgap right-hgap rem:w-[40px] rem:h-[40px] z-[100] hidden bg-primary transition-all duration-400 hover:bg-foreground rounded-full" style="display: none;">
		<?php ap_svg( 'arrow-back-to-top', null, 'rem:h-[20px] rem:w-[20px] z-[101] absCenter fill-white' ); ?>
	</a>
	<?php
}

/**
 * Prevent wysiwyg from removing span tags
 */
function ap_wp_override_mce_options( $initArray ) {
	$opts                                 = '*[*]';
	$initArray['valid_elements']          = $opts;
	$initArray['extended_valid_elements'] = $opts;
	return $initArray;
}
add_filter( 'tiny_mce_before_init', 'ap_wp_override_mce_options' );

// Relevanssi Search results Organizing
add_filter( 'relevanssi_hits_filter', 'separate_result_types' );
function separate_result_types( $hits ) {

	$types            = array();
	$types['product'] = array();
	$types['post']    = array();
	$types['page']    = array();

	// Split the post types in array $types
	if ( ! empty( $hits ) ) {
		foreach ( $hits[0] as $hit ) {
			if ( ! is_array( $types[ $hit->post_type ] ) ) {
				$types[ $hit->post_type ] = array();
			}
			array_push( $types[ $hit->post_type ], $hit );
		}
	}

	// Merge back to $hits in the desired order
	$hits[0] = array_merge( $types['product'], $types['post'], $types['page'] );
	return $hits;
}

/**
 * Flex Content Auto Loader
 *
 * @author Alessio Pangos
 * Auto loads layouts for the ACF Flex Content Page Builder. All classes need to have a render method to be displayed correctly.
 */
function ap_wp_theme_flexible_content_auto_loader() {

	// check if the flexible content field has rows of data
	if ( have_rows( 'pb_elements_flexible' ) ) :

		// loop through the selected ACF layouts and display the matching class Block
		while ( have_rows( 'pb_elements_flexible' ) ) :
			the_row();
			$className = 'Classes\\Blocks\\' . get_row_layout();
			$thisBlock = new $className();
			$thisBlock->render();
		endwhile;

	endif;
}

/**
 * Load Popup
 */
add_action( 'ap_wp_after_footer', 'ap_wp_popup' );
function ap_wp_popup() {
	$popUpEverywhere = new \Classes\Core\APWPPopup();
}

/**
 * Load Flexible Archive Filters if enabled
 */
if ( FLEX_FILTERS_ENABLED ) :

	/**
	 * Creates a new FlexibleArchiveFilters instance and calls a method, according to the parameter passed to it
	 *  layouts: vertical, horizontal
	 *  methods: categories, categories_global, product_tags
	 */
	\Classes\Core\FlexibleArchiveFiltersHelpers::shortcode_functions();

	/**
	 * Pre Get Posts filters for Flexible Archive Filters
	 */
	\Classes\Core\FlexibleArchiveFiltersHelpers::pre_get_posts_filter();

endif;

/**
 * Remove CF7 auto P
 */
add_filter( 'wpcf7_autop_or_not', '__return_false' );

add_action( 'wp_footer', 'ap_wp_breakpoints_check' );
function ap_wp_breakpoints_check() {
	?>
	<span id="md-breakpoint-check" class="inline md:hidden"></span><span id="lg-breakpoint-check" class="inline lg:hidden"></span><span id="xl-breakpoint-check" class="inline xl:hidden"></span>
	<?php
}

/**
 * Exclude featured posts from main blog loop
 */
function ap_wp_exclude_featured_from_blog_index( $query ) {
	if ( ! is_admin() && $query->is_home() && $query->is_main_query() ) {
		$post_ids = ap_wp_featured_post_ids();
		if ( ! empty( $post_ids ) ) {
			$query->set( 'post__not_in', $post_ids );
		}
	}
}
add_action( 'pre_get_posts', 'ap_wp_exclude_featured_from_blog_index' );
/*
* Query featured posts
* Includes the first 3 featured by default. Adjust $num for less or more.
*/
function ap_wp_featured_post_ids( $num = 3 ) {
	$post_ids = array();

	$args = array(
		'post_type'   => 'post',
		'post_status' => 'publish',
		'showposts'   => $num,
		'orderby'     => 'date',
		'order'       => 'desc',
		'meta_query'  => array(
			array(
				'key'     => 'featured_post',
				'value'   => 1,
				'compare' => '=',
			),
		),
	);

	$c_query = new \WP_Query( $args );

	if ( $c_query->have_posts() ) {
		while ( $c_query->have_posts() ) {
			$c_query->the_post();
			$post_ids[] = get_the_ID();
		}
	}
	wp_reset_postdata();

	return $post_ids;
}


function ap_wp_theme_archive_cat_filters() {
	?>
	<div class="layout-container relative mx-auto pb-hhgap">
		<span class="bottomCenter w-[calc(100%-theme(spacing.2gap))] block border-black border-b"></span>
		<div class="flex flex-col mdd:gap-hgap md:flex-row md:items-center justify-start mt-svgap md:mt-vgap">

			<?php
			$args = array(
				'taxonomy'   => 'category',
				'hide_empty' => true,  // Only show categories with at least one product
			);

			// Get all categories
			$categories = get_terms( $args );

			// Get the WooCommerce blog page URL
			$blog_page_url = get_permalink( get_option( 'page_for_posts' ) );

			// Get the current page ID
			$current_page_id = get_queried_object_id();

			// Determine if we are on the blog page
			$is_blog_page = is_home();

			// Start outputting the list
			echo '<ul class="md:ml-auto flex gap-2gap">';

			// "All entries" entry
			$all_entries_class = $is_blog_page ? 'font-bold' : '';
			echo '<li class="' . esc_attr( $all_entries_class ) . '">';
			echo '<a href="' . esc_url( $blog_page_url ) . '">' . __( 'Tutti i racconti', 'ap-wp-theme' ) . '</a>';
			echo '</li>';

			// Check if we have any categories to display
			if ( ! empty( $categories ) ) {
				foreach ( $categories as $category ) {
					// Determine if the current category is being viewed
					$is_current_category = ( $current_page_id == $category->term_id );

					// Set the CSS class for the category item
					$class = $is_current_category ? 'font-bold' : '';

					// Display the category
					echo '<li class="' . esc_attr( $class ) . '">';
					echo '<a href="' . esc_url( get_term_link( $category ) ) . '">' . esc_html( $category->name ) . '</a>';
					echo '</li>';
				}
			} else {
				echo '<p>No categories available with entries.</p>';
			}

			echo '</ul>';
			?>
		</div>
	</div>
	<?php
}
