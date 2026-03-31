<?php
/**
 * The header for our theme
 *
 * Minimal shell — header content is built with blocks.
 *
 * @package kahu
 */

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php wp_body_open(); ?>

<div id="page">
	<a href="#main" class="screen-reader-text"><?php esc_html_e( 'Skip to content', 'kahu' ); ?></a>

	<header id="masthead" class="site-header">
		<?php do_action( 'kahu_header' ); ?>
	</header>

	<div id="content">
