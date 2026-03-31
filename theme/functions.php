<?php
/**
 * Kahunam Base Theme functions and definitions
 *
 * Minimal theme shell — header and footer are built with blocks.
 *
 * @package kahu
 */

if ( ! defined( 'KAHU_VERSION' ) ) {
	define( 'KAHU_VERSION', '0.1.0' );
}

if ( ! isset( $content_width ) ) {
	$content_width = 860;
}

if ( ! function_exists( 'kahu_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 */
	function kahu_setup() {
		load_theme_textdomain( 'kahu', get_template_directory() . '/languages' );

		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'custom-logo' );

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

		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'editor-styles' );
		add_editor_style( array( 'css/framework.css', 'css/blocks.css' ) );
		add_theme_support( 'align-wide' );
		remove_theme_support( 'block-templates' );
	}
endif;
add_action( 'after_setup_theme', 'kahu_setup' );

/**
 * Enqueue scripts and styles.
 */
function kahu_scripts() {
	wp_enqueue_style( 'kahu-framework', get_template_directory_uri() . '/css/framework.css', array(), KAHU_VERSION );
	wp_enqueue_style( 'kahu-blocks', get_template_directory_uri() . '/css/blocks.css', array( 'kahu-framework' ), KAHU_VERSION );
	wp_enqueue_style( 'kahu-theme', get_template_directory_uri() . '/css/theme.css', array( 'kahu-blocks' ), KAHU_VERSION );
	wp_enqueue_style( 'kahu-style', get_stylesheet_uri(), array( 'kahu-theme' ), KAHU_VERSION );
	wp_enqueue_style( 'kahu-override', get_template_directory_uri() . '/css/override.css', array( 'kahu-style' ), KAHU_VERSION );
}
add_action( 'wp_enqueue_scripts', 'kahu_scripts' );

/**
 * Register custom post meta fields exposed via REST API.
 */
function kahu_register_meta() {
	$meta_args = array(
		'show_in_rest'  => true,
		'single'        => true,
		'type'          => 'boolean',
		'default'       => false,
		'auth_callback' => function () {
			return current_user_can( 'edit_posts' );
		},
	);

	register_post_meta( '', '_kahu_hide_title', $meta_args );
	register_post_meta( '', '_kahu_hide_featured_image', $meta_args );
}
add_action( 'init', 'kahu_register_meta' );

/**
 * Add body classes for page template and meta options.
 */
function kahu_body_classes( $classes ) {
	if ( is_singular() ) {
		if ( get_post_meta( get_the_ID(), '_kahu_hide_title', true ) ) {
			$classes[] = 'hide-title';
		}
		if ( get_post_meta( get_the_ID(), '_kahu_hide_featured_image', true ) ) {
			$classes[] = 'hide-featured-image';
		}
	}
	return $classes;
}
add_filter( 'body_class', 'kahu_body_classes' );
