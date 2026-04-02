# Kahunam Base Theme - Kahunam Blocks Integration Plan

**Date:** 2026-04-02
**Goal:** Make the theme work seamlessly with Kahunam Blocks for dynamic header/footer, sidebar, layout control, and core block styling - all accessible via REST API.

---

## Current State

The theme is a minimal classic PHP theme with:
- Simple hardcoded header (text link, no nav)
- Simple hardcoded footer (copyright only)
- No hook system for template part injection
- No REST-exposed page layout meta
- No dynamic sidebar support
- `block-templates: false` / `block-template-parts: false`

Kahunam Blocks cannot inject header/footer because:
- No theme hooks to attach to
- `render_block()` and `do_blocks()` don't work for kahunam blocks from external PHP (Code Snippets)
- The theme needs to call the Kahunam Blocks plugin API directly

---

## 1. Hook System

Add `do_action()` calls at key template positions so external code (Code Snippets, child themes, plugins) can inject content.

### header.php

```php
<?php do_action('kahunam_before_header'); ?>

<header id="masthead" class="site-header">
    <?php
    // If a kahunam block is registered for this position, render it instead
    if (has_action('kahunam_header')) {
        do_action('kahunam_header');
    } else {
        // Default header markup
        ?>
        <div class="content-container ...">
            <a href="<?php echo esc_url(home_url('/')); ?>" ...>
                <?php bloginfo('name'); ?>
            </a>
        </div>
        <?php
    }
    ?>
</header>

<?php do_action('kahunam_after_header'); ?>
```

### footer.php

```php
<?php do_action('kahunam_before_footer'); ?>

<footer id="colophon" class="site-footer">
    <?php
    if (has_action('kahunam_footer')) {
        do_action('kahunam_footer');
    } else {
        // Default footer markup
        ?>
        <div class="content-container ...">
            &copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>
        </div>
        <?php
    }
    ?>
</footer>

<?php do_action('kahunam_after_footer'); ?>
```

### Additional hooks needed in templates:

| Hook | Location | Purpose |
|------|----------|---------|
| `kahunam_before_header` | Before `<header>` | Inject announcement bars, skip links |
| `kahunam_header` | Replaces header content | Custom header block |
| `kahunam_after_header` | After `</header>` | Breadcrumbs, sub-nav |
| `kahunam_before_content` | Before main content in page/single | Hero sections, page-level overrides |
| `kahunam_after_content` | After main content | Related posts, CTAs |
| `kahunam_sidebar` | Sidebar position | Dynamic sidebar content |
| `kahunam_before_footer` | Before `<footer>` | Pre-footer CTAs, newsletter |
| `kahunam_footer` | Replaces footer content | Custom footer block |
| `kahunam_after_footer` | After `</footer>` | Scripts, modals |

---

## 2. Kahunam Blocks Render Helper

Add a helper function in `functions.php` that renders any Kahunam Block by slug:

```php
/**
 * Render a Kahunam Block by slug.
 *
 * @param string $slug  Block slug without namespace (e.g., 'kd-site-header')
 * @param array  $attrs Block attributes to pass
 * @return string|false Rendered HTML or false if block not found
 */
function kahunam_render_block($slug, $attrs = array()) {
    if (!function_exists('kahunam_blocks')) {
        return false;
    }

    $blocks = kahunam_blocks()->blocks();
    $full_slug = 'kahunam/' . $slug;
    $block = $blocks->get_block($full_slug);

    if (!$block) {
        return false;
    }

    return $block->render($attrs);
}
```

This allows Code Snippets or child themes to render blocks:

```php
// In a Code Snippet:
add_action('kahunam_header', function() {
    echo kahunam_render_block('kd-site-header');
});
```

---

## 3. Page Layout Meta (REST-Exposed)

Register custom post meta fields that control page layout, accessible via REST API.

### Meta fields to register:

| Meta Key | Type | Values | Default | Purpose |
|----------|------|--------|---------|---------|
| `_kahunam_layout` | string | `default`, `full-width`, `narrow`, `contained` | `default` | Controls content container width |
| `_kahunam_hide_title` | boolean | true/false | false | Hides the page/post title |
| `_kahunam_hide_featured_image` | boolean | true/false | false | Hides the featured image |
| `_kahunam_sidebar` | string | `none`, `right`, `left` | `none` | Sidebar position |

### Registration in functions.php:

```php
add_action('init', function() {
    $post_types = array('post', 'page');

    foreach ($post_types as $pt) {
        register_post_meta($pt, '_kahunam_layout', array(
            'show_in_rest' => true,
            'single'       => true,
            'type'         => 'string',
            'default'      => 'default',
            'auth_callback' => function() { return current_user_can('edit_posts'); },
        ));

        register_post_meta($pt, '_kahunam_hide_title', array(
            'show_in_rest' => true,
            'single'       => true,
            'type'         => 'boolean',
            'default'      => false,
            'auth_callback' => function() { return current_user_can('edit_posts'); },
        ));

        register_post_meta($pt, '_kahunam_hide_featured_image', array(
            'show_in_rest' => true,
            'single'       => true,
            'type'         => 'boolean',
            'default'      => false,
            'auth_callback' => function() { return current_user_can('edit_posts'); },
        ));

        register_post_meta($pt, '_kahunam_sidebar', array(
            'show_in_rest' => true,
            'single'       => true,
            'type'         => 'string',
            'default'      => 'none',
            'auth_callback' => function() { return current_user_can('edit_posts'); },
        ));
    }
});
```

### REST API usage:

```bash
# Set a page to full-width with hidden title
POST /wp-json/wp/v2/pages/{id}
{ "meta": { "_kahunam_layout": "full-width", "_kahunam_hide_title": true } }

# Set a post to have right sidebar
POST /wp-json/wp/v2/posts/{id}
{ "meta": { "_kahunam_sidebar": "right" } }
```

---

## 4. Template Hierarchy Updates

### page.php / single.php

Templates should read the meta values and adjust rendering:

```php
<?php
$layout = get_post_meta(get_the_ID(), '_kahunam_layout', true) ?: 'default';
$hide_title = get_post_meta(get_the_ID(), '_kahunam_hide_title', true);
$hide_image = get_post_meta(get_the_ID(), '_kahunam_hide_featured_image', true);
$sidebar = get_post_meta(get_the_ID(), '_kahunam_sidebar', true) ?: 'none';

// Map layout to container class
$container_class = 'content-container';
switch ($layout) {
    case 'full-width':
        $container_class .= ' layout-full-width'; // no max-width
        break;
    case 'narrow':
        $container_class .= ' layout-narrow'; // max-width: 760px
        break;
    case 'contained':
        $container_class .= ' layout-contained'; // max-width: 960px
        break;
}

// Sidebar wrapper
$has_sidebar = ($sidebar !== 'none');
?>

<?php do_action('kahunam_before_content'); ?>

<div class="<?php echo esc_attr($container_class); ?> <?php echo $has_sidebar ? 'has-sidebar sidebar-' . $sidebar : ''; ?>">
    <main id="main" class="site-main">
        <?php if (!$hide_title): ?>
            <h1 class="entry-title"><?php the_title(); ?></h1>
        <?php endif; ?>

        <?php if (!$hide_image && has_post_thumbnail()): ?>
            <div class="entry-featured-image">
                <?php the_post_thumbnail('large'); ?>
            </div>
        <?php endif; ?>

        <div class="entry-content">
            <?php the_content(); ?>
        </div>
    </main>

    <?php if ($has_sidebar): ?>
        <aside class="site-sidebar">
            <?php do_action('kahunam_sidebar'); ?>
        </aside>
    <?php endif; ?>
</div>

<?php do_action('kahunam_after_content'); ?>
```

### Layout CSS classes:

```css
.layout-full-width { max-width: 100%; padding: 0; }
.layout-narrow { max-width: 760px; margin: 0 auto; }
.layout-contained { max-width: 960px; margin: 0 auto; }
.has-sidebar { display: grid; gap: 40px; }
.sidebar-right { grid-template-columns: 1fr 300px; }
.sidebar-left { grid-template-columns: 300px 1fr; }
@media (max-width: 960px) {
    .has-sidebar { grid-template-columns: 1fr; }
}
```

### Existing page templates

The theme already has `page-full-width.php` and `page-contained.php`. These should be kept for backwards compatibility but the meta-based approach should be the primary method since it's REST-accessible.

---

## 5. Base Styles for WordPress Core Blocks

The theme needs default styles for all standard WP blocks so content renders correctly. Add to `style.css` or a dedicated `css/blocks.css`:

### Blocks to style:

| Block | Key Styles Needed |
|-------|------------------|
| `core/heading` | Font sizes h1-h6, font-family, line-height, margins |
| `core/paragraph` | Font size, line-height, margin-bottom, max-width for readability |
| `core/list` | Padding, marker style, spacing between items |
| `core/image` | Border-radius, max-width 100%, caption styling |
| `core/quote` | Left border, padding, italic, background |
| `core/table` | Border-collapse, header bg, cell padding, responsive overflow |
| `core/button` | Brand colors, border-radius, padding, hover states |
| `core/columns` | Gap, responsive stacking breakpoint |
| `core/group` | Padding for nested groups |
| `core/separator` | Color, thickness, margin |
| `core/spacer` | (WP handles this, but ensure no overflow) |
| `core/code` | Mono font, background, padding, overflow-x |
| `core/preformatted` | Same as code |
| `core/pullquote` | Large font, centered, accent border |
| `core/cover` | Min-height, overlay, text alignment |
| `core/media-text` | Responsive stacking, image sizing |
| `core/gallery` | Gap, border-radius on thumbnails |
| `core/embed` | Responsive aspect-ratio wrapper |

### Key CSS variables to expose:

```css
:root {
    --ka-color-primary: #1868DB;
    --ka-color-dark: #04112a;
    --ka-color-accent: #00d66f;
    --ka-color-heading: #111827;
    --ka-color-body: #374151;
    --ka-color-muted: #9ca3af;
    --ka-color-border: #e5e7eb;
    --ka-color-bg: #f8f9fa;
    --ka-font-heading: 'Instrument Sans', sans-serif;
    --ka-font-body: 'Inter', sans-serif;
    --ka-font-serif: 'Instrument Serif', Georgia, serif;
    --ka-font-mono: 'Fira Code', monospace;
    --ka-container-default: 1200px;
    --ka-container-narrow: 760px;
    --ka-container-contained: 960px;
    --ka-radius: 8px;
}
```

---

## Implementation Order

1. **Hook system** (header.php, footer.php, page.php, single.php) - enables block injection
2. **Render helper** (functions.php) - `kahunam_render_block()` function
3. **Page layout meta** (functions.php) - REST-exposed meta registration
4. **Template updates** (page.php, single.php, archive.php) - read meta and adjust layout
5. **Core block styles** (style.css or css/blocks.css) - default styling for all WP blocks

---

## Files to Modify

| File | Changes |
|------|---------|
| `header.php` | Add hooks, conditional block rendering |
| `footer.php` | Add hooks, conditional block rendering |
| `functions.php` | Add render helper, register meta, enqueue block styles |
| `page.php` | Read layout meta, conditional title/image, sidebar support |
| `single.php` | Same as page.php |
| `archive.php` | Layout support, hooks |
| `style.css` | Core block styles, layout classes, CSS variables |
| `theme.json` | Add color palette, font sizes, spacing presets matching CSS vars |
