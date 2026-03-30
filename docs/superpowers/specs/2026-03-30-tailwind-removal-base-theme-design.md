# Kahunam Base Theme — Tailwind Removal + Verbose Framework

## Goal

Strip the `_tw`-based WordPress theme to a minimal, zero-build-step base theme powered by the Verbose Framework CSS. No Tailwind, no PostCSS, no esbuild, no npm build pipeline.

---

## Remove entirely

### Files to delete

- `tailwind/` directory (all contents)
- `tailwind.css`
- `postcss.config.js`
- `eslint.config.js`
- `prettier.config.js`
- `package.json`
- `package-lock.json`
- `composer.json`
- `composer.lock`
- `phpcs.xml.dist`
- `node_scripts/` directory
- `javascript/` directory (both `script.js` and `block-editor.js`)
- `README.md` (the current one documents the _tw workflow)
- `theme/js/` directory (contains compiled build output and a readme.txt)

### Code to remove from PHP

- `KAHU_TYPOGRAPHY_CLASSES` constant (functions.php)
- `kahu_content_class()` function (template-tags.php)
- `kahu_enqueue_block_editor_script()` function (functions.php)
- `kahu_tinymce_add_class()` function (functions.php)
- `kahu_modify_heading_levels()` function (functions.php)
- All `kahu_content_class()` calls in templates (replace with plain `class="entry-content"`)
- All Tailwind utility classes from PHP templates (e.g. `sr-only` becomes `.screen-reader-text` — defined in `theme.css`)
- Block editor script enqueue
- `style-editor.css` editor style enqueue

---

## Add

### `theme/css/framework.css`

Copy of Verbose Framework v1.0 from `kahunam-tools/css-framework/framework.css`. Self-contained in the theme — no external dependency.

### `theme/css/theme.css`

Project-level stylesheet for:

- `:root` variable overrides for the theme's brand colors (uses theme.json palette values)
- Visually-hidden utility (`.screen-reader-text` — WordPress convention)
- Header styles: sticky header, mobile menu show/hide
- Footer styles: layout for logo/tagline row, columns, copyright
- Mobile menu transition/animation

### `theme/js/navigation.js`

~15 lines vanilla JS:

- Selects the menu toggle button and the nav element
- Toggles a `.menu-open` class on the nav
- Updates `aria-expanded` on the button
- No dependencies, no build step

### `theme/images/logo.svg`

Placeholder SVG logo. Simple geometric shape with "Logo" text.

---

## Keep (with modifications)

### `theme/functions.php`

- Remove `KAHU_TYPOGRAPHY_CLASSES` constant and all references
- Remove `kahu_enqueue_block_editor_script()` and its `add_action`
- Remove `kahu_tinymce_add_class()` and its `add_filter`
- Remove `kahu_modify_heading_levels()` and its `add_filter`
- Remove `add_theme_support('editor-styles')` and `add_editor_style()`
- Update `kahu_scripts()` to enqueue `css/framework.css`, `css/theme.css`, and `js/navigation.js` instead of the old assets
- Keep widget sidebar registered but it will not be called in templates by default
- Register 5 nav menus: `primary`, `footer-1`, `footer-2`, `footer-3`, `footer-4`
- Keep all other theme supports (post-thumbnails, title-tag, html5, etc.)

### `theme/style.css`

WordPress file header only (theme name, version, author metadata). No actual CSS — all styles live in `css/framework.css` and `css/theme.css`.

### `theme/header.php`

- `<!doctype html>`, `<head>`, `wp_head()`
- `<body>`, `wp_body_open()`
- Skip to content link

### `theme/template-parts/layout/header-content.php`

```
<header id="masthead" class="position-sticky background-color-white border-bottom-subtle">
  <div class="content-container align-container-center flex flex-align-items-center flex-justify-content-space-between padding-vertical-medium">
    <a href="/" class="link-unstyled" rel="home">
      <!-- Placeholder SVG logo -->
    </a>
    <nav id="site-navigation" aria-label="Main Navigation">
      <button class="menu-toggle display-none-on-desktop" aria-controls="primary-menu" aria-expanded="false">
        <!-- Hamburger icon SVG -->
      </button>
      <?php wp_nav_menu(...) ?>
    </nav>
  </div>
</header>
```

- Logo on left, nav on right
- Menu toggle button only visible on mobile/tablet (`display-none-on-desktop` from framework — hidden above 768px)
- Nav menu hidden on mobile by default, revealed via `.menu-open` class

### `theme/template-parts/layout/footer-content.php`

```
<footer id="colophon" class="background-color-dark text-color-white padding-vertical-extra-large">
  <div class="content-container align-container-center">

    <!-- Logo + tagline -->
    <div class="margin-bottom-large">
      <!-- SVG logo (white version or with text-color-white) -->
      <p class="font-size-small text-color-muted margin-top-small">Tagline here</p>
    </div>

    <!-- 4-column nav grid -->
    <div class="grid grid-four-columns gap-large margin-bottom-large">
      <div class="vertical-link-list">
        <h3 class="font-size-small font-weight-bold margin-bottom-small">Column Title</h3>
        <?php wp_nav_menu(['theme_location' => 'footer-1', ...]) ?>
      </div>
      <!-- repeat for footer-2, footer-3, footer-4 -->
    </div>

    <!-- Copyright -->
    <div class="border-top-subtle padding-top-medium font-size-extra-small text-color-muted">
      &copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All rights reserved.
    </div>

  </div>
</footer>
```

Columns collapse via framework's built-in responsive rules (4 cols -> 2 at 1024px -> 1 at 768px).

### `theme/footer.php`

- Closes `#content`, includes footer template part, `wp_footer()`, closes `</body></html>`

### `theme/inc/template-tags.php`

- Remove `kahu_content_class()` function entirely
- Replace `sr-only` references with `.screen-reader-text`
- Keep: `kahu_posted_on`, `kahu_posted_by`, `kahu_comment_count`, `kahu_entry_meta`, `kahu_entry_footer`, `kahu_post_thumbnail`, `kahu_the_posts_navigation`, avatar helpers

### `theme/inc/template-functions.php`

- Remove `kahu_html5_comment()` usage of `kahu_content_class()` — use plain `class="entry-content"` instead
- Keep: `kahu_pingback_header`, `kahu_comment_form_defaults`, `kahu_get_the_archive_title`, `kahu_can_show_post_thumbnail`, `kahu_get_avatar_size`, `kahu_continue_reading_link`, `kahu_html5_comment`

### Content templates

- `index.php`, `single.php`, `page.php`, `archive.php`, `search.php`, `404.php`
- Clean up: replace any Tailwind classes, use Verbose Framework classes where needed
- `single.php` and `page.php`: comments_template() call commented out by default with a note saying "Uncomment to enable comments"

### `theme/comments.php`

- Kept as-is (with Tailwind class cleanup)
- Not called from templates by default

### `theme/theme.json`

- Keep for WordPress block editor color palette integration
- Update if needed to match Verbose Framework variable colors

---

## Nav menu registrations

| Location    | Label        | Usage                   |
|-------------|--------------|-------------------------|
| `primary`   | Primary      | Header main navigation  |
| `footer-1`  | Footer Col 1 | Footer column 1         |
| `footer-2`  | Footer Col 2 | Footer column 2         |
| `footer-3`  | Footer Col 3 | Footer column 3         |
| `footer-4`  | Footer Col 4 | Footer column 4         |

---

## Widget & comments strategy

- **Widgets:** Sidebar `sidebar-1` stays registered in `functions.php`. Not rendered in any template by default. To enable, uncomment `dynamic_sidebar('sidebar-1')` in the relevant template.
- **Comments:** `comments.php` stays in theme. `comments_template()` call in `single.php` and `page.php` is commented out. To enable, uncomment the line.

---

## Enqueued assets (front-end)

| Handle             | File                  | Type | Deps |
|--------------------|-----------------------|------|------|
| `kahu-framework`   | `css/framework.css`   | CSS  | none |
| `kahu-theme`       | `css/theme.css`       | CSS  | `kahu-framework` |
| `kahu-navigation`  | `js/navigation.js`    | JS   | none |

No editor styles. No block editor JS.

---

## Out of scope

- Custom fonts (use framework defaults)
- WooCommerce support
- Gutenberg block styles
- Any build step whatsoever
