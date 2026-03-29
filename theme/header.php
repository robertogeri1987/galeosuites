<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package ap-wp-theme-tw
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<link rel="stylesheet" href="https://use.typekit.net/obl1mly.css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<?php do_action('ap_wp_before'); ?>
	<div class="clearfix" id="page">
		<a href="#content" class="sr-only" x-data x-cloak x-breakpoint="if ($isBreakpoint('md+')) {$store.header.setNavMode('desktop');}if ($isBreakpoint('sm-')) {$store.header.setNavMode('mobile');}"><?php esc_html_e('Skip to content', 'ap-wp-theme-tw'); ?></a>

		<?php get_template_part('template-parts/layout/header', 'content'); ?>

		<div id="content" class="max-w-screen min-h-screen">