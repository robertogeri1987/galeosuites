<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package ap-wp-theme
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<aside id="secondary" class="sidebar sidebar-primary widget-area md:min-w-[theme('spacing.sidebar')] md:mt-hhgap" role="complementary" aria-label="Sidebar">
	<?php
	echo '<h2 class="screen-reader-text">' . __( 'Sidebar', 'ap_wp_theme' ) . '</h2>';
	dynamic_sidebar( 'sidebar-1' );
	?>
</aside><!-- #secondary -->
