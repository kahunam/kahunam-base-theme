# Kahunam Base Theme — Tailwind Removal + Verbose Framework Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Strip the _tw WordPress theme of all Tailwind CSS and build tooling, replace with the Verbose Framework, and create a minimal base theme with a simple header (logo + mobile menu) and footer (logo/tagline + 4 nav columns + copyright).

**Architecture:** Classic WordPress theme with zero build steps. CSS delivered via two static files: the Verbose Framework (utility classes) and a theme stylesheet (overrides + component styles). One vanilla JS file for mobile menu toggle. All Tailwind-specific PHP code removed; templates updated to use Verbose Framework classes.

**Tech Stack:** PHP (WordPress theme API), CSS (Verbose Framework custom properties + utilities), vanilla JavaScript.

---

## File Map

### Files to delete

- `tailwind/` (entire directory — 6 files)
- `tailwind.css`
- `postcss.config.js`
- `eslint.config.js`
- `prettier.config.js`
- `package.json`
- `package-lock.json`
- `composer.json`
- `composer.lock`
- `phpcs.xml.dist`
- `node_scripts/` (entire directory)
- `javascript/` (entire directory)
- `README.md`
- `theme/js/readme.txt`

### Files to create

| File | Responsibility |
|------|---------------|
| `theme/css/framework.css` | Verbose Framework v1.0 (copied from kahunam-tools) |
| `theme/css/theme.css` | Variable overrides, `.screen-reader-text`, header/footer/mobile-menu styles |
| `theme/js/navigation.js` | Mobile menu toggle (~15 lines) |
| `theme/images/logo.svg` | Placeholder SVG logo |

### Files to modify

| File | Changes |
|------|---------|
| `theme/functions.php` | Remove all Tailwind code, update enqueues, update nav menu registrations |
| `theme/style.css` | Update header metadata, remove all CSS content |
| `theme/header.php` | No changes needed (delegates to template part) |
| `theme/footer.php` | No changes needed (delegates to template part) |
| `theme/template-parts/layout/header-content.php` | Complete rewrite — logo + nav + mobile toggle |
| `theme/template-parts/layout/footer-content.php` | Complete rewrite — logo/tagline + 4 columns + copyright |
| `theme/inc/template-tags.php` | Remove `kahu_content_class()`, replace `sr-only` with `screen-reader-text` |
| `theme/inc/template-functions.php` | Replace `kahu_content_class()` call in comment callback, replace `sr-only` |
| `theme/404.php` | Replace `kahu_content_class()` call |
| `theme/single.php` | Comment out `comments_template()`, replace `sr-only` |
| `theme/page.php` | Comment out `comments_template()` |
| `theme/template-parts/content/content.php` | Replace `kahu_content_class()` call |
| `theme/template-parts/content/content-single.php` | Replace `kahu_content_class()` and `sr-only` |
| `theme/template-parts/content/content-page.php` | Replace `kahu_content_class()` and `sr-only` |
| `theme/template-parts/content/content-excerpt.php` | Replace `kahu_content_class()` call |
| `theme/template-parts/content/content-none.php` | Replace `kahu_content_class()` call |
| `theme/theme.json` | Update colors to match Verbose Framework defaults |

---

## Task 1: Delete all Tailwind and build tooling files

**Files:**
- Delete: `tailwind/` directory, `tailwind.css`, `postcss.config.js`, `eslint.config.js`, `prettier.config.js`, `package.json`, `package-lock.json`, `composer.json`, `composer.lock`, `phpcs.xml.dist`, `node_scripts/` directory, `javascript/` directory, `README.md`, `theme/js/readme.txt`

- [ ] **Step 1: Delete all build tooling and Tailwind files**

```bash
rm -rf tailwind/ node_scripts/ javascript/ theme/js/
rm -f tailwind.css postcss.config.js eslint.config.js prettier.config.js
rm -f package.json package-lock.json composer.json composer.lock phpcs.xml.dist
rm -f README.md theme/js/readme.txt
```

- [ ] **Step 2: Verify only theme/ and docs/ remain**

```bash
ls -la
```

Expected: only `theme/`, `docs/`, `LICENSE`, `.git/`, `.DS_Store` remain at root.

- [ ] **Step 3: Commit**

```bash
git add -A
git commit -m "chore: remove Tailwind CSS, build tooling, and npm/composer dependencies"
```

---

## Task 2: Add Verbose Framework, theme CSS, navigation JS, and placeholder logo

**Files:**
- Create: `theme/css/framework.css`
- Create: `theme/css/theme.css`
- Create: `theme/js/navigation.js`
- Create: `theme/images/logo.svg`

- [ ] **Step 1: Create the css and js directories**

```bash
mkdir -p theme/css theme/js theme/images
```

- [ ] **Step 2: Copy Verbose Framework into theme**

```bash
cp /Users/scottdooley/Documents/GitHub/kahunam-tools/css-framework/framework.css theme/css/framework.css
```

- [ ] **Step 3: Create `theme/css/theme.css`**

```css
/* ============================================================
 *  Kahunam Base Theme — Project Overrides
 *  Override Verbose Framework variables and add theme-specific
 *  component styles here.
 * ============================================================ */


/* ==========================================================================
   VARIABLE OVERRIDES
   ========================================================================== */

:root {
  --color-background: #ffffff;
  --color-foreground: #121212;
  --color-primary: #052962;
  --color-accent: #c70000;
}


/* ==========================================================================
   SCREEN READER TEXT (WordPress convention)
   ========================================================================== */

.screen-reader-text {
  border: 0;
  clip: rect(1px, 1px, 1px, 1px);
  clip-path: inset(50%);
  height: 1px;
  margin: -1px;
  overflow: hidden;
  padding: 0;
  position: absolute;
  width: 1px;
  word-wrap: normal !important;
}

.screen-reader-text:focus {
  background-color: #f1f1f1;
  border-radius: 3px;
  box-shadow: 0 0 2px 2px rgba(0, 0, 0, 0.6);
  clip: auto !important;
  clip-path: none;
  color: #21759b;
  display: block;
  font-size: 0.875rem;
  font-weight: 700;
  height: auto;
  left: 5px;
  line-height: normal;
  padding: 15px 23px 14px;
  text-decoration: none;
  top: 5px;
  width: auto;
  z-index: 100000;
}


/* ==========================================================================
   SITE HEADER
   ========================================================================== */

.site-header {
  position: sticky;
  top: 0;
  z-index: 100;
  background-color: var(--color-background);
  border-bottom: 1px solid var(--color-border);
}

.site-header .header-inner {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding-top: var(--spacing-medium);
  padding-bottom: var(--spacing-medium);
}

.site-logo svg {
  height: 32px;
  width: auto;
}


/* ==========================================================================
   MOBILE MENU
   ========================================================================== */

.menu-toggle {
  display: none;
  background: none;
  border: none;
  cursor: pointer;
  padding: var(--spacing-small);
}

.menu-toggle svg {
  width: 24px;
  height: 24px;
  display: block;
}

/* Primary menu — horizontal on desktop */
#primary-menu {
  display: flex;
  gap: var(--spacing-large);
  list-style: none;
}

#primary-menu a {
  color: var(--color-foreground);
  text-decoration: none;
  font-size: var(--font-size-small);
  font-weight: var(--font-weight-medium);
  transition: color var(--transition-speed-base);
}

#primary-menu a:hover {
  color: var(--color-primary);
}

@media (max-width: 768px) {
  .menu-toggle {
    display: block;
  }

  #site-navigation .menu {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background-color: var(--color-background);
    border-bottom: 1px solid var(--color-border);
    padding: var(--spacing-medium) var(--container-padding-horizontal);
  }

  #site-navigation.menu-open .menu {
    display: block;
  }

  #primary-menu {
    flex-direction: column;
    gap: var(--spacing-medium);
  }
}


/* ==========================================================================
   SITE FOOTER
   ========================================================================== */

.site-footer {
  background-color: var(--color-foreground);
  color: #ffffff;
  padding-top: var(--spacing-extra-large);
  padding-bottom: var(--spacing-large);
}

.site-footer a {
  color: rgba(255, 255, 255, 0.7);
  text-decoration: none;
  transition: color var(--transition-speed-base);
}

.site-footer a:hover {
  color: #ffffff;
}

.footer-logo svg {
  height: 28px;
  width: auto;
}

.footer-tagline {
  font-size: var(--font-size-small);
  color: rgba(255, 255, 255, 0.5);
  margin-top: var(--spacing-small);
}

.footer-nav-columns {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: var(--spacing-large);
}

.footer-nav-column h3 {
  font-family: var(--font-family-body);
  font-size: var(--font-size-extra-small);
  font-weight: var(--font-weight-bold);
  text-transform: uppercase;
  letter-spacing: 0.08em;
  color: rgba(255, 255, 255, 0.5);
  margin-bottom: var(--spacing-medium);
}

.footer-nav-column .menu {
  display: flex;
  flex-direction: column;
  gap: 0.4em;
  list-style: none;
}

.footer-nav-column .menu a {
  font-size: var(--font-size-small);
}

.footer-copyright {
  border-top: 1px solid rgba(255, 255, 255, 0.15);
  padding-top: var(--spacing-medium);
  font-size: var(--font-size-extra-small);
  color: rgba(255, 255, 255, 0.4);
}

@media (max-width: 1024px) {
  .footer-nav-columns {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 768px) {
  .footer-nav-columns {
    grid-template-columns: 1fr;
  }
}


/* ==========================================================================
   CONTENT LAYOUT
   ========================================================================== */

#primary {
  max-width: var(--container-width-content);
  margin-left: auto;
  margin-right: auto;
  padding-left: var(--container-padding-horizontal);
  padding-right: var(--container-padding-horizontal);
  padding-top: var(--spacing-extra-large);
  padding-bottom: var(--spacing-extra-large);
}
```

- [ ] **Step 4: Create `theme/js/navigation.js`**

```javascript
/**
 * Mobile navigation toggle.
 */
( function() {
	const nav = document.getElementById( 'site-navigation' );
	if ( ! nav ) {
		return;
	}

	const button = nav.querySelector( '.menu-toggle' );
	if ( ! button ) {
		return;
	}

	button.addEventListener( 'click', function() {
		const expanded = button.getAttribute( 'aria-expanded' ) === 'true';
		button.setAttribute( 'aria-expanded', String( ! expanded ) );
		nav.classList.toggle( 'menu-open' );
	} );
} )();
```

- [ ] **Step 5: Create `theme/images/logo.svg`**

```svg
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120 32" fill="currentColor">
  <rect width="32" height="32" rx="4" />
  <text x="40" y="23" font-family="system-ui, sans-serif" font-size="18" font-weight="700">Logo</text>
</svg>
```

- [ ] **Step 6: Commit**

```bash
git add theme/css/framework.css theme/css/theme.css theme/js/navigation.js theme/images/logo.svg
git commit -m "feat: add Verbose Framework, theme CSS, navigation JS, and placeholder logo"
```

---

## Task 3: Rewrite `functions.php`

**Files:**
- Modify: `theme/functions.php`

- [ ] **Step 1: Replace `theme/functions.php` with cleaned version**

```php
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
```

- [ ] **Step 2: Commit**

```bash
git add theme/functions.php
git commit -m "feat: rewrite functions.php — remove Tailwind, add Verbose Framework enqueues and footer nav menus"
```

---

## Task 4: Update `style.css` header and `theme.json`

**Files:**
- Modify: `theme/style.css`
- Modify: `theme/theme.json`

- [ ] **Step 1: Replace `theme/style.css` with header-only content**

```css
/*!
Theme Name: Kahunam Base
Theme URI: https://kahunam.com
Description: Minimal WordPress base theme powered by the Verbose Framework CSS.
Version: 0.1.0
Author: Kahunam
Author URI: https://kahunam.com
Text Domain: kahu
Requires at least: 6.2
Requires PHP: 7.4
License: GNU General Public License v2 or later
License URI: LICENSE
*/
```

- [ ] **Step 2: Replace `theme/theme.json` with updated colors**

```json
{
	"$schema": "https://schemas.wp.org/trunk/theme.json",
	"version": 3,
	"settings": {
		"color": {
			"palette": [
				{
					"slug": "background",
					"color": "#ffffff",
					"name": "Background"
				},
				{
					"slug": "foreground",
					"color": "#121212",
					"name": "Foreground"
				},
				{
					"slug": "primary",
					"color": "#052962",
					"name": "Primary"
				},
				{
					"slug": "accent",
					"color": "#c70000",
					"name": "Accent"
				},
				{
					"slug": "muted",
					"color": "#767676",
					"name": "Muted"
				}
			]
		},
		"layout": {
			"contentSize": "860px",
			"wideSize": "1300px"
		}
	}
}
```

- [ ] **Step 3: Commit**

```bash
git add theme/style.css theme/theme.json
git commit -m "chore: update style.css header and theme.json colors to match Verbose Framework"
```

---

## Task 5: Rewrite header and footer template parts

**Files:**
- Modify: `theme/template-parts/layout/header-content.php`
- Modify: `theme/template-parts/layout/footer-content.php`

- [ ] **Step 1: Replace `theme/template-parts/layout/header-content.php`**

```php
<?php
/**
 * Template part for displaying the header content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package kahu
 */

?>

<header id="masthead" class="site-header">
	<div class="content-container align-container-center header-inner">

		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo link-unstyled" rel="home" aria-label="<?php bloginfo( 'name' ); ?>">
			<?php echo file_get_contents( get_template_directory() . '/images/logo.svg' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents ?>
		</a>

		<nav id="site-navigation" aria-label="<?php esc_attr_e( 'Main Navigation', 'kahu' ); ?>">
			<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false" aria-label="<?php esc_attr_e( 'Menu', 'kahu' ); ?>">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<line x1="3" y1="6" x2="21" y2="6"/>
					<line x1="3" y1="12" x2="21" y2="12"/>
					<line x1="3" y1="18" x2="21" y2="18"/>
				</svg>
			</button>

			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'menu_id'        => 'primary-menu',
					'container'      => 'div',
					'container_class' => 'menu',
					'fallback_cb'    => false,
				)
			);
			?>
		</nav><!-- #site-navigation -->

	</div>
</header><!-- #masthead -->
```

- [ ] **Step 2: Replace `theme/template-parts/layout/footer-content.php`**

```php
<?php
/**
 * Template part for displaying the footer content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package kahu
 */

?>

<footer id="colophon" class="site-footer">
	<div class="content-container align-container-center">

		<div class="margin-bottom-large">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="footer-logo link-unstyled" rel="home" aria-label="<?php bloginfo( 'name' ); ?>">
				<?php echo file_get_contents( get_template_directory() . '/images/logo.svg' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents ?>
			</a>
			<?php
			$kahu_description = get_bloginfo( 'description', 'display' );
			if ( $kahu_description || is_customize_preview() ) :
				?>
				<p class="footer-tagline"><?php echo esc_html( $kahu_description ); ?></p>
			<?php endif; ?>
		</div>

		<div class="footer-nav-columns margin-bottom-large">
			<?php
			$footer_menus = array(
				'footer-1' => __( 'Column 1', 'kahu' ),
				'footer-2' => __( 'Column 2', 'kahu' ),
				'footer-3' => __( 'Column 3', 'kahu' ),
				'footer-4' => __( 'Column 4', 'kahu' ),
			);

			foreach ( $footer_menus as $location => $label ) :
				if ( has_nav_menu( $location ) ) :
					?>
					<div class="footer-nav-column">
						<h3><?php echo esc_html( wp_get_nav_menu_name( $location ) ); ?></h3>
						<?php
						wp_nav_menu(
							array(
								'theme_location' => $location,
								'depth'          => 1,
								'fallback_cb'    => false,
							)
						);
						?>
					</div>
					<?php
				endif;
			endforeach;
			?>
		</div>

		<div class="footer-copyright">
			&copy; <?php echo esc_html( date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'All rights reserved.', 'kahu' ); ?>
		</div>

	</div>
</footer><!-- #colophon -->
```

- [ ] **Step 3: Commit**

```bash
git add theme/template-parts/layout/header-content.php theme/template-parts/layout/footer-content.php
git commit -m "feat: rewrite header and footer templates with Verbose Framework classes"
```

---

## Task 6: Clean up template-tags.php

**Files:**
- Modify: `theme/inc/template-tags.php`

- [ ] **Step 1: Replace `theme/inc/template-tags.php`**

Remove `kahu_content_class()` (the entire function). Replace every `sr-only` reference with `screen-reader-text`. Keep all other functions.

```php
<?php
/**
 * Custom template tags for this theme
 *
 * @package kahu
 */

if ( ! function_exists( 'kahu_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function kahu_posted_on() {
		$time_string = '<time class="published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_html( get_the_modified_date() )
		);

		printf(
			'<a href="%1$s" rel="bookmark">%2$s</a>',
			esc_url( get_permalink() ),
			$time_string // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		);
	}
endif;

if ( ! function_exists( 'kahu_posted_by' ) ) :
	/**
	 * Prints HTML with meta information about theme author.
	 */
	function kahu_posted_by() {
		printf(
			'<span class="screen-reader-text">%1$s</span><span class="author vcard"><a class="url fn n" href="%2$s">%3$s</a></span>',
			esc_html__( 'Posted by', 'kahu' ),
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_html( get_the_author() )
		);
	}
endif;

if ( ! function_exists( 'kahu_comment_count' ) ) :
	/**
	 * Prints HTML with the comment count for the current post.
	 */
	function kahu_comment_count() {
		if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			comments_popup_link( sprintf( __( 'Leave a comment<span class="screen-reader-text"> on %s</span>', 'kahu' ), get_the_title() ) );
		}
	}
endif;

if ( ! function_exists( 'kahu_entry_meta' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 * This template tag is used in the entry header.
	 */
	function kahu_entry_meta() {

		if ( 'post' === get_post_type() ) {

			kahu_posted_by();
			kahu_posted_on();

			$categories_list = get_the_category_list( __( ', ', 'kahu' ) );
			if ( $categories_list ) {
				printf(
					'<span><span class="screen-reader-text">%1$s</span>%2$s</span>',
					esc_html__( 'Posted in', 'kahu' ),
					$categories_list // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				);
			}

			$tags_list = get_the_tag_list( '', __( ', ', 'kahu' ) );
			if ( $tags_list ) {
				printf(
					'<span><span class="screen-reader-text">%1$s</span>%2$s</span>',
					esc_html__( 'Tags:', 'kahu' ),
					$tags_list // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				);
			}
		}

		if ( ! is_singular() ) {
			kahu_comment_count();
		}

		edit_post_link(
			sprintf(
				wp_kses(
					__( 'Edit <span class="screen-reader-text">%s</span>', 'kahu' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			)
		);
	}
endif;

if ( ! function_exists( 'kahu_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function kahu_entry_footer() {

		if ( 'post' === get_post_type() ) {

			kahu_posted_by();
			kahu_posted_on();

			$categories_list = get_the_category_list( __( ', ', 'kahu' ) );
			if ( $categories_list ) {
				printf(
					'<span><span class="screen-reader-text">%1$s</span>%2$s</span>',
					esc_html__( 'Posted in', 'kahu' ),
					$categories_list // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				);
			}

			$tags_list = get_the_tag_list( '', __( ', ', 'kahu' ) );
			if ( $tags_list ) {
				printf(
					'<span><span class="screen-reader-text">%1$s</span>%2$s</span>',
					esc_html__( 'Tags:', 'kahu' ),
					$tags_list // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				);
			}
		}

		if ( ! is_singular() ) {
			kahu_comment_count();
		}

		edit_post_link(
			sprintf(
				wp_kses(
					__( 'Edit <span class="screen-reader-text">%s</span>', 'kahu' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			)
		);
	}
endif;

if ( ! function_exists( 'kahu_post_thumbnail' ) ) :
	/**
	 * Displays an optional post thumbnail.
	 */
	function kahu_post_thumbnail() {
		if ( ! kahu_can_show_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) :
			?>
			<figure>
				<?php the_post_thumbnail(); ?>
			</figure>
			<?php
		else :
			?>
			<figure>
				<a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
					<?php the_post_thumbnail(); ?>
				</a>
			</figure>
			<?php
		endif;
	}
endif;

if ( ! function_exists( 'kahu_comment_avatar' ) ) :
	/**
	 * Returns the HTML markup to generate a user avatar.
	 *
	 * @param mixed $id_or_email The Gravatar to retrieve.
	 */
	function kahu_get_user_avatar_markup( $id_or_email = null ) {
		if ( ! isset( $id_or_email ) ) {
			$id_or_email = get_current_user_id();
		}

		return sprintf( '<div class="vcard">%s</div>', get_avatar( $id_or_email, kahu_get_avatar_size() ) );
	}
endif;

if ( ! function_exists( 'kahu_discussion_avatars_list' ) ) :
	/**
	 * Displays a list of avatars involved in a discussion for a given post.
	 *
	 * @param array $comment_authors Comment authors to list as avatars.
	 */
	function kahu_discussion_avatars_list( $comment_authors ) {
		if ( empty( $comment_authors ) ) {
			return;
		}
		echo '<ol>', "\n";
		foreach ( $comment_authors as $id_or_email ) {
			printf(
				"<li>%s</li>\n",
				kahu_get_user_avatar_markup( $id_or_email ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
		}
		echo '</ol>', "\n";
	}
endif;

if ( ! function_exists( 'kahu_the_posts_navigation' ) ) :
	/**
	 * Wraps `the_posts_pagination` for use throughout the theme.
	 */
	function kahu_the_posts_navigation() {
		the_posts_pagination(
			array(
				'mid_size'  => 2,
				'prev_text' => __( 'Newer posts', 'kahu' ),
				'next_text' => __( 'Older posts', 'kahu' ),
			)
		);
	}
endif;
```

- [ ] **Step 2: Commit**

```bash
git add theme/inc/template-tags.php
git commit -m "refactor: remove kahu_content_class, replace sr-only with screen-reader-text in template tags"
```

---

## Task 7: Clean up template-functions.php

**Files:**
- Modify: `theme/inc/template-functions.php`

- [ ] **Step 1: Replace `theme/inc/template-functions.php`**

Replace the `kahu_content_class()` call inside `kahu_html5_comment()` with a plain `class="entry-content"`. Replace `sr-only` with `screen-reader-text` in `kahu_continue_reading_link()`.

```php
<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package kahu
 */

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function kahu_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'kahu_pingback_header' );

/**
 * Changes comment form default fields.
 *
 * @param array $defaults The default comment form arguments.
 * @return array Returns the modified fields.
 */
function kahu_comment_form_defaults( $defaults ) {
	$comment_field = $defaults['comment_field'];
	$defaults['comment_field'] = preg_replace( '/rows="\d+"/', 'rows="5"', $comment_field );
	return $defaults;
}
add_filter( 'comment_form_defaults', 'kahu_comment_form_defaults' );

/**
 * Filters the default archive titles.
 */
function kahu_get_the_archive_title() {
	if ( is_category() ) {
		$title = __( 'Category Archives: ', 'kahu' ) . '<span>' . single_term_title( '', false ) . '</span>';
	} elseif ( is_tag() ) {
		$title = __( 'Tag Archives: ', 'kahu' ) . '<span>' . single_term_title( '', false ) . '</span>';
	} elseif ( is_author() ) {
		$title = __( 'Author Archives: ', 'kahu' ) . '<span>' . get_the_author_meta( 'display_name' ) . '</span>';
	} elseif ( is_year() ) {
		$title = __( 'Yearly Archives: ', 'kahu' ) . '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'kahu' ) ) . '</span>';
	} elseif ( is_month() ) {
		$title = __( 'Monthly Archives: ', 'kahu' ) . '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'kahu' ) ) . '</span>';
	} elseif ( is_day() ) {
		$title = __( 'Daily Archives: ', 'kahu' ) . '<span>' . get_the_date() . '</span>';
	} elseif ( is_post_type_archive() ) {
		$cpt   = get_post_type_object( get_queried_object()->name );
		$title = sprintf(
			esc_html__( '%s Archives', 'kahu' ),
			$cpt->labels->singular_name
		);
	} elseif ( is_tax() ) {
		$tax   = get_taxonomy( get_queried_object()->taxonomy );
		$title = sprintf(
			esc_html__( '%s Archives', 'kahu' ),
			$tax->labels->singular_name
		);
	} else {
		$title = __( 'Archives:', 'kahu' );
	}
	return $title;
}
add_filter( 'get_the_archive_title', 'kahu_get_the_archive_title' );

/**
 * Determines whether the post thumbnail can be displayed.
 */
function kahu_can_show_post_thumbnail() {
	return apply_filters( 'kahu_can_show_post_thumbnail', ! post_password_required() && ! is_attachment() && has_post_thumbnail() );
}

/**
 * Returns the size for avatars used in the theme.
 */
function kahu_get_avatar_size() {
	return 60;
}

/**
 * Create the continue reading link.
 *
 * @param string $more_string The string shown within the more link.
 */
function kahu_continue_reading_link( $more_string ) {
	if ( ! is_admin() ) {
		$continue_reading = sprintf(
			wp_kses( __( 'Continue reading %s', 'kahu' ), array( 'span' => array( 'class' => array() ) ) ),
			the_title( '<span class="screen-reader-text">"', '"</span>', false )
		);

		$more_string = '<a href="' . esc_url( get_permalink() ) . '">' . $continue_reading . '</a>';
	}

	return $more_string;
}
add_filter( 'excerpt_more', 'kahu_continue_reading_link' );
add_filter( 'the_content_more_link', 'kahu_continue_reading_link' );

/**
 * Outputs a comment in the HTML5 format.
 *
 * @param WP_Comment $comment Comment to display.
 * @param array      $args    An array of arguments.
 * @param int        $depth   Depth of the current comment.
 */
function kahu_html5_comment( $comment, $args, $depth ) {
	$tag = ( 'div' === $args['style'] ) ? 'div' : 'li';

	$commenter          = wp_get_current_commenter();
	$show_pending_links = ! empty( $commenter['comment_author'] );

	if ( $commenter['comment_author_email'] ) {
		$moderation_note = __( 'Your comment is awaiting moderation.', 'kahu' );
	} else {
		$moderation_note = __( 'Your comment is awaiting moderation. This is a preview; your comment will be visible after it has been approved.', 'kahu' );
	}
	?>
	<<?php echo esc_attr( $tag ); ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( $comment->has_children ? 'parent' : '', $comment ); ?>>
		<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
			<footer class="comment-meta">
				<div class="comment-author vcard">
					<?php
					if ( 0 !== $args['avatar_size'] ) {
						echo get_avatar( $comment, $args['avatar_size'] );
					}
					?>
					<?php
					$comment_author = get_comment_author_link( $comment );

					if ( '0' === $comment->comment_approved && ! $show_pending_links ) {
						$comment_author = get_comment_author( $comment );
					}

					printf(
						wp_kses_post( __( '%s <span class="says">says:</span>', 'kahu' ) ),
						sprintf( '<b class="fn">%s</b>', wp_kses_post( $comment_author ) )
					);
					?>
				</div><!-- .comment-author -->

				<div class="comment-metadata">
					<?php
					printf(
						'<a href="%s"><time datetime="%s">%s</time></a>',
						esc_url( get_comment_link( $comment, $args ) ),
						esc_attr( get_comment_time( 'c' ) ),
						esc_html(
							sprintf(
								__( '%1$s at %2$s', 'kahu' ),
								get_comment_date( '', $comment ),
								get_comment_time()
							)
						)
					);

					edit_comment_link( __( 'Edit', 'kahu' ), ' <span class="edit-link">', '</span>' );
					?>
				</div><!-- .comment-metadata -->

				<?php if ( '0' === $comment->comment_approved ) : ?>
				<em class="comment-awaiting-moderation"><?php echo esc_html( $moderation_note ); ?></em>
				<?php endif; ?>
			</footer><!-- .comment-meta -->

			<div class="entry-content">
				<?php comment_text(); ?>
			</div><!-- .comment-content -->

			<?php
			if ( '1' === $comment->comment_approved || $show_pending_links ) {
				comment_reply_link(
					array_merge(
						$args,
						array(
							'add_below' => 'div-comment',
							'depth'     => $depth,
							'max_depth' => $args['max_depth'],
							'before'    => '<div class="reply">',
							'after'     => '</div>',
						)
					)
				);
			}
			?>
		</article><!-- .comment-body -->
	<?php
}
```

- [ ] **Step 2: Commit**

```bash
git add theme/inc/template-functions.php
git commit -m "refactor: remove Tailwind references from template-functions.php"
```

---

## Task 8: Clean up content template parts and page templates

**Files:**
- Modify: `theme/template-parts/content/content.php`
- Modify: `theme/template-parts/content/content-single.php`
- Modify: `theme/template-parts/content/content-page.php`
- Modify: `theme/template-parts/content/content-excerpt.php`
- Modify: `theme/template-parts/content/content-none.php`
- Modify: `theme/404.php`
- Modify: `theme/single.php`
- Modify: `theme/page.php`

- [ ] **Step 1: Update `theme/template-parts/content/content.php`**

Replace `<div <?php kahu_content_class( 'entry-content' ); ?>>` with `<div class="entry-content">`.

- [ ] **Step 2: Update `theme/template-parts/content/content-single.php`**

Replace `<div <?php kahu_content_class( 'entry-content' ); ?>>` with `<div class="entry-content">`.
Replace `class="sr-only"` with `class="screen-reader-text"`.

- [ ] **Step 3: Update `theme/template-parts/content/content-page.php`**

Replace `<div <?php kahu_content_class( 'entry-content' ); ?>>` with `<div class="entry-content">`.
Replace `class="sr-only"` with `class="screen-reader-text"`.

- [ ] **Step 4: Update `theme/template-parts/content/content-excerpt.php`**

Replace `<div <?php kahu_content_class( 'entry-content' ); ?>>` with `<div class="entry-content">`.

- [ ] **Step 5: Update `theme/template-parts/content/content-none.php`**

Replace `<div <?php kahu_content_class( 'page-content' ); ?>>` with `<div class="page-content">`.

- [ ] **Step 6: Update `theme/404.php`**

Replace `<div <?php kahu_content_class( 'page-content' ); ?>>` with `<div class="page-content">`.

- [ ] **Step 7: Update `theme/single.php`**

Replace `sr-only` with `screen-reader-text`. Comment out `comments_template()`:

Change:
```php
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}
```
To:
```php
				// Uncomment below to enable comments.
				// if ( comments_open() || get_comments_number() ) {
				// 	comments_template();
				// }
```

- [ ] **Step 8: Update `theme/page.php`**

Comment out `comments_template()`:

Change:
```php
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}
```
To:
```php
				// Uncomment below to enable comments.
				// if ( comments_open() || get_comments_number() ) {
				// 	comments_template();
				// }
```

- [ ] **Step 9: Commit**

```bash
git add theme/template-parts/content/ theme/404.php theme/single.php theme/page.php
git commit -m "refactor: replace Tailwind classes in content templates, disable comments by default"
```

---

## Task 9: Update header.php skip link

**Files:**
- Modify: `theme/header.php`

- [ ] **Step 1: Update the skip-to-content link class**

Replace `class="sr-only"` with `class="screen-reader-text"` on line 27.

- [ ] **Step 2: Commit**

```bash
git add theme/header.php
git commit -m "refactor: update skip link class to screen-reader-text"
```

---

## Task 10: Final verification

- [ ] **Step 1: Check no Tailwind references remain**

```bash
grep -r "tailwind\|sr-only\|kahu_content_class\|TYPOGRAPHY_CLASSES\|prose\|not-prose" theme/ --include="*.php" --include="*.css" --include="*.js"
```

Expected: no matches.

- [ ] **Step 2: Check no references to deleted files**

```bash
grep -r "block-editor\|style-editor\|script\.min\.js" theme/ --include="*.php"
```

Expected: no matches.

- [ ] **Step 3: Verify file structure**

```bash
find . -not -path './.git/*' -not -name '.DS_Store' -not -path './docs/*' | sort
```

Expected structure:
```
.
./LICENSE
./theme
./theme/404.php
./theme/archive.php
./theme/comments.php
./theme/css
./theme/css/framework.css
./theme/css/theme.css
./theme/footer.php
./theme/functions.php
./theme/header.php
./theme/images
./theme/images/logo.svg
./theme/inc
./theme/inc/template-functions.php
./theme/inc/template-tags.php
./theme/index.php
./theme/js
./theme/js/navigation.js
./theme/languages
./theme/languages/readme.txt
./theme/page.php
./theme/screenshot.png
./theme/search.php
./theme/single.php
./theme/style.css
./theme/template-parts
./theme/template-parts/content
./theme/template-parts/content/content-excerpt.php
./theme/template-parts/content/content-none.php
./theme/template-parts/content/content-page.php
./theme/template-parts/content/content-single.php
./theme/template-parts/content/content.php
./theme/template-parts/layout
./theme/template-parts/layout/footer-content.php
./theme/template-parts/layout/header-content.php
./theme/theme.json
```

- [ ] **Step 4: Final commit if any fixes were needed**

```bash
git status
```

If clean, done. If any fixes were made during verification, commit them:

```bash
git add -A
git commit -m "fix: clean up remaining Tailwind references found during verification"
```
