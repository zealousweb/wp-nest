---
trigger: glob
globs: *.php
---

# PHP

## Structure

```
functions.php                → setup + requires
includes/theme-functions.php → helpers
includes/theme-action.php    → actions + AJAX
includes/theme-filter.php    → filters
includes/theme-security.php  → security
includes/acf-block-register.php → ACF blocks + render
includes/acf-json/           → ACF JSON
template-parts/blocks/       → block templates
```

**Rule:** One file = one responsibility. Thin templates, fat helpers.

## Security

- Add `ABSPATH` guard to every PHP file.
- Escape output:
  - Text → `esc_html()`
  - Attr → `esc_attr()`
  - URL → `esc_url()`
  - HTML → `wp_kses_post()`
- Sanitize all input.
- AJAX → nonce + `wp_send_json_*()`.
- DB → `$wpdb->prepare()`.
- Never use `eval()`, `extract()`, or `query_posts()`.

## ACF Blocks

- Single `theme_acf_block_render_callback()`.
- Template flow: assign → validate → return → escape.
- Use `acf_link()` for link fields.
- Use `acf_image()` for image fields.

## Queries

- Use `WP_Query`.
- Set `no_found_rows => true` when not paginating.

## Standards

- Use the `TEXT_DOMAIN` constant for all translation functions, and `THEME_PREFIX` for all hook handles.
- Use `get_template_directory()` / `_uri()`.
- Follow WPCS (tabs, casting, max 3 nesting levels).

**Never:** Business logic in `functions.php`, duplicate helpers, raw `echo get_field()`, hardcoded `<img>`, or hardcoded asset filenames.
