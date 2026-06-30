---
trigger: always_on
---

# PHP — SITE_NAME

## Architecture

```
functions.php               → setup + requires only
includes/theme-scripts.php  → themeCSS() / themeJS() enqueue
includes/theme-functions.php → helpers
includes/theme-action.php   → action hooks + AJAX handlers
includes/theme-filter.php   → filter hooks
includes/theme-security.php → security hardening
includes/acf-block-register.php → block registration + render callback
includes/acf-json/          → ACF field JSON (source of truth)
template-parts/blocks/      → block templates
```

Thin templates, fat helpers. One responsibility per include.

## Security — every PHP file

ABSPATH guard (see `03-acf-blocks.md` for exact pattern). Escaping:

| Output | Function |
|--------|----------|
| Text | `esc_html()` / `esc_html__()` |
| Attrs | `esc_attr()` / `esc_attr__()` |
| URLs display | `esc_url()` |
| URLs store/redirect | `esc_url_raw()` / `wp_safe_redirect()` |
| HTML | `wp_kses_post()` |
| Sanitize input | `sanitize_text_field()`, `absint()`, `wp_unslash() + sanitize_*` |

AJAX: `wp_verify_nonce()` + `wp_send_json_error()` + `wp_die()` — see `theme-action.php` for pattern. DB: `$wpdb->prepare()` always. Never `eval()`, `extract()`, unserialize user data.

## Enqueue (`includes/theme-scripts.php`)

`themeJS($key)` and `themeCSS($key)` read `assets/manifest.json` — never hardcode hashed filenames. Pass PHP data to JS via `wp_localize_script()` as `wpnest_ajax_object`.

## ACF blocks

Render callback: `theme_acf_block_render_callback()` — defined once in `acf-block-register.php`, never duplicate. Block templates: assign → validate → early `return` → escape output. Full pattern: `acf.mdc`.

## Images

`wp_get_attachment_image($id, $size, false, $attrs)` only — never hardcoded `<img>`. Hero/LCP: `$attrs = ['loading' => 'eager', 'fetchpriority' => 'high']`.

## Queries

Never `query_posts()`. `no_found_rows => true` on `WP_Query` when no pagination needed.

## Conventions

- `TEXT_DOMAIN` on all translatable strings
- New helpers: prefixed with `THEME_PREFIX . '_'` · existing boilerplate helpers (`themeCSS`, `themeJS`, `acf_svg`) are unprefixed — match the file's style
- `get_template_directory()` / `get_template_directory_uri()` for all paths
- WPCS: tabs · cast IDs `(int)` · null coalesce `$x = $val ?? ''` · max 3 nesting levels in templates

**Never:** business logic in `functions.php` · duplicate helpers · `query_posts()` · `echo get_field()` raw.
