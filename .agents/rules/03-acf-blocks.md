---
trigger: always_on
---

# WPNest — ACF Blocks Rules

## How It Works

All blocks use a **single shared render callback** (`theme_acf_block_render_callback`).
It strips the `acf/` prefix from the block name and auto-loads the matching template:

```
Block name: acf/my_block
Template:   template-parts/blocks/my_block.php
```

If the template file is missing, a visible error renders in the editor.

---

## Template (`template-parts/blocks/{slug}.php`)

Every block template must start with:

```php
if ( ! defined( 'ABSPATH' ) ) { header('Status: 403 Forbidden'); exit; }
```

## Block Wrapper (ID & Class)

Every block must support custom `anchor`, `className` properties dynamically in its outer wrapper:

```php
// Support custom "anchor" values.
$block_id = '{block-prefix}-' . $block['id'];
if ( ! empty( $block['anchor'] ) ) {
	$block_id = $block['anchor'];
}

// Create class attribute allowing for custom "className" values.
$class_name = '{block-name}';
if ( ! empty( $block['className'] ) ) {
	$class_name .= ' ' . $block['className'];
}
```

Apply these to the section wrapper:
```php
<section id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $class_name ); ?>">
```


## File Locations

| Purpose | Path |
|---|---|
| Register blocks | `includes/acf-block-register.php` |
| Block templates | `template-parts/blocks/{block-name}.php` |
| ACF field JSON | `includes/acf-json/` |

---

## Conditionals — no empty output ever

1. No content → `return` early
2. Repeaters: `have_rows()` / `the_row()` · `$row['key'] ?? []` · empty row → `continue`
3. Check every field: `if ($heading)` · `if ($image_id)` · `if (!empty($link['url']))`
4. Wrappers only render if children render
5. Escaping: `esc_html()` text · `wp_kses_post()` WYSIWYG · `esc_url()` URLs

**Never:** `echo get_field()` raw · `<img>` with ID 0 · `href="#"` · wrappers with no children.

## Adding a New Block — Checklist

1. **Register** in `includes/acf-block-register.php`
2. **Create template** at `template-parts/blocks/{name}.php`
3. **Create ACF field group** in WP Admin → Custom Fields, assign location to block
4. **Sync JSON** — ACF auto-saves to `includes/acf-json/` on field save; commit the JSON file

---

**Never create a new render callback.** Always reuse `theme_acf_block_render_callback`.

---

## Block CSS & JS

- SCSS: create `sources/scss/components/_comp-{BLOCKNAME}.scss` → import in `components/_core.scss`
- JS: create `sources/js/modules/{BLOCKNAME}.js` with named export → lazy import in `script.js`
- **JS Libraries & Scoping**: When an ACF block uses a JavaScript library (like Swiper, Fancybox, etc.) or custom JS logic, the script must **only** be imported in `script.js` when the block is present on the page (conditional lazy loading). Furthermore, the initialization logic must be scoped entirely to that specific block's container (e.g. `block.querySelectorAll('.swiper')`) to avoid side effects on other parts of the site.

---
