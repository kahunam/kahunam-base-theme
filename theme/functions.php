<?php
/**
 * Kahunam Base Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package kahu
 */

if ( ! defined( 'KAHU_VERSION' ) ) {
	define( 'KAHU_VERSION', '0.1.0' );
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

		register_nav_menus(
			array(
				'primary'  => __( 'Primary', 'kahu' ),
				'footer-1' => __( 'Footer Col 1', 'kahu' ),
				'footer-2' => __( 'Footer Col 2', 'kahu' ),
				'footer-3' => __( 'Footer Col 3', 'kahu' ),
				'footer-4' => __( 'Footer Col 4', 'kahu' ),
			)
		);

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

		add_theme_support( 'customize-selective-refresh-widgets' );
		add_theme_support( 'responsive-embeds' );
		remove_theme_support( 'block-templates' );
	}
endif;
add_action( 'after_setup_theme', 'kahu_setup' );

/**
 * Register widget area.
 */
function kahu_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Footer', 'kahu' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Add widgets here to appear in your footer.', 'kahu' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'kahu_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function kahu_scripts() {
	wp_enqueue_style( 'kahu-framework', get_template_directory_uri() . '/css/framework.css', array(), KAHU_VERSION );
	wp_enqueue_style( 'kahu-theme', get_template_directory_uri() . '/css/theme.css', array( 'kahu-framework' ), KAHU_VERSION );
	wp_enqueue_style( 'kahu-style', get_stylesheet_uri(), array( 'kahu-theme' ), KAHU_VERSION );
	wp_enqueue_script( 'kahu-navigation', get_template_directory_uri() . '/js/navigation.js', array(), KAHU_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'kahu_scripts' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';
