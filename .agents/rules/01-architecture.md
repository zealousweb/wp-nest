---
trigger: manual
---

# WPNest — Architecture Rules

## Directory Map
```
wp-nest/
├── functions.php              # Bootstrap only — require calls, zero logic
├── includes/                  # All PHP modules
│   ├── theme-scripts.php      # Enqueue JS/CSS via manifest
│   ├── theme-functions.php    # Utility/helper functions
│   ├── theme-action.php       # add_action hooks
│   ├── theme-filter.php       # add_filter hooks
│   ├── theme-security.php     # Security hardening
│   ├── acf-block-register.php # ACF block registration
│   └── acf-json/              # ACF field group JSON sync
├── template-parts/blocks/     # One PHP file per ACF block
├── sources/                   # Raw editable source files
│   ├── scss/                  # SCSS source (7-1 pattern)
│   └── js/                    # ES module JS source
└── assets/                    # Webpack compiled output — NEVER EDIT
    ├── css/
    ├── js/
    └── manifest.json          # Asset fingerprint map
```

## Rules
- `functions.php` = `require` calls only. Zero logic.
- Each concern gets its own `includes/` file. Never mix actions/filters/utilities.
- Never edit anything under `assets/` — it is build output.
- ACF field JSON lives in `includes/acf-json/`. Keep in sync with DB.
- Block template filename must match `acf/` block name slug exactly.
- New block → register in `acf-block-register.php` + create `template-parts/blocks/{name}.php`.

## File Responsibility Map
| File | Owns |
|---|---|
| `functions.php` | `require` only |
| `theme-scripts.php` | `wp_enqueue_*` via `themeCSS()`/`themeJS()` |
| `theme-functions.php` | Helper/utility functions |
| `theme-action.php` | All `add_action()` calls |
| `theme-filter.php` | All `add_filter()` calls |
| `theme-security.php` | Headers, nonce, sanitisation globals |
| `acf-block-register.php` | All `acf_register_block_type()` calls |

## New ACF Block — Full Checklist
When creating any ACF block, always complete **all** of these steps:

1. **Register** the block in `includes/acf-block-register.php` using `theme_acf_block_render_callback`.
2. **Create template** at `template-parts/blocks/{block-name}.php` — filename must match the block slug exactly.
3. **Create SCSS** at `sources/scss/components/_comp-{block-name}.scss` (or `_{block-name}.scss` if the name is unique).
4. **Import SCSS** — add `@use "{filename}"` in `sources/scss/components/_core.scss`.
5. **Create ACF JSON** at `includes/acf-json/group_{block-name}.json` — assign location to `acf/{block-name}`.

## Block Naming Quick-Reference
> Full rules in `04-scss.md`. Summary for quick use:
