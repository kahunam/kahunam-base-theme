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

		register_nav_menus(
			array(
				'primary' => __( 'Primary', 'kahu' ),
			)
		);
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
 * Disable Elements meta keys — all exposed via REST API.
 */
function kahu_disable_elements_keys() {
	return array(
		'_kahu_disable_top_bar',
		'_kahu_disable_header',
		'_kahu_disable_primary_navigation',
		'_kahu_disable_featured_image',
		'_kahu_disable_content_title',
		'_kahu_disable_footer',
	);
}

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

	foreach ( kahu_disable_elements_keys() as $key ) {
		register_post_meta( '', $key, $meta_args );
	}
}
add_action( 'init', 'kahu_register_meta' );

/**
 * Enqueue the Disable Elements sidebar panel in the block editor.
 */
function kahu_enqueue_editor_assets() {
	wp_enqueue_script(
		'kahu-disable-elements',
		get_template_directory_uri() . '/js/disable-elements.js',
		array( 'wp-plugins', 'wp-edit-post', 'wp-components', 'wp-data', 'wp-element' ),
		KAHU_VERSION,
		true
	);
}
add_action( 'enqueue_block_editor_assets', 'kahu_enqueue_editor_assets' );

/**
 * Add body classes based on Disable Elements meta.
 */
function kahu_body_classes( $classes ) {
	if ( is_singular() ) {
		$map = array(
			'_kahu_disable_top_bar'            => 'disable-top-bar',
			'_kahu_disable_header'             => 'disable-header',
			'_kahu_disable_primary_navigation' => 'disable-primary-navigation',
			'_kahu_disable_featured_image'     => 'disable-featured-image',
			'_kahu_disable_content_title'      => 'disable-content-title',
			'_kahu_disable_footer'             => 'disable-footer',
		);

		foreach ( $map as $meta_key => $class ) {
			if ( get_post_meta( get_the_ID(), $meta_key, true ) ) {
				$classes[] = $class;
			}
		}
	}
	return $classes;
}
add_filter( 'body_class', 'kahu_body_classes' );

/**
 * Helper — check if an element is disabled for the current post.
 */
function kahu_is_disabled( $key ) {
	if ( ! is_singular() ) {
		return false;
	}
	return (bool) get_post_meta( get_the_ID(), $key, true );
}

/**
 * Fallback header — shows site title + primary nav.
 */
function kahu_default_header() {
	if ( kahu_is_disabled( '_kahu_disable_header' ) ) {
		return;
	}
	?>
	<div class="content-container align-container-center flex-direction-row align-items-center justify-content-space-between padding-vertical-medium">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="link-unstyled font-weight-bold" style="font-size: var(--font-size-large);">
			<?php bloginfo( 'name' ); ?>
		</a>
		<?php if ( ! kahu_is_disabled( '_kahu_disable_primary_navigation' ) ) : ?>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => 'nav',
					'menu_id'        => 'primary-menu',
					'depth'          => 1,
					'fallback_cb'    => false,
				)
			);
			?>
		<?php endif; ?>
	</div>
	<?php
}
add_action( 'kahu_header', 'kahu_default_header' );

/**
 * Fallback footer — shows site name + year.
 */
function kahu_default_footer() {
	if ( kahu_is_disabled( '_kahu_disable_footer' ) ) {
		return;
	}
	?>
	<div class="content-container align-container-center padding-vertical-large" style="border-top: 1px solid var(--color-border); font-size: var(--font-size-small); color: var(--color-muted);">
		&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>
	</div>
	<?php
}
add_action( 'kahu_footer', 'kahu_default_footer' );
