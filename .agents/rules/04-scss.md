---
trigger: always_on
---

# WPNest — SCSS Rules

## 7-1 Structure
```
sources/scss/
├── abstracts/
│   ├── _variables.scss   # CSS custom properties + SCSS maps  ← source of truth
│   ├── _mixins.scss      # All mixins                         ← source of truth
│   └── _function.scss    # rem(), pxtopercent(), aspect-ratio(), ls(), image(), font()
├── base/                 # normalize, typography, buttons, forms, utilities, wordpress
├── components/           # _core.scss imports component partials
├── layout/               # header, footer, device-menu, default
├── templates/            # page-specific overrides
└── style.scss            # Entry point — @use each _core only
```

## Import Rule — always `@use`, never `@import`
```scss
@use "@scss/abstracts/variables" as *;
@use "@scss/abstracts/mixins" as *;
@use "@scss/abstracts/function" as *;
```

> **Variables** → see `abstracts/_variables.scss`
> **Mixins** → see `abstracts/_mixins.scss`
> **Functions** → see `abstracts/_function.scss`

## Naming Convention

### ACF Block class structure — 3-tier pattern
Every ACF block template follows this exact three-tier structure:

```html
<section class="{blockname}">            <!-- 1. Section wrapper (scopes all inner styles) -->
    <div class="container">              <!-- 2. Global container utility (never a custom div) -->
        <div class="{prefix}-header">    <!-- 3. Short prefix for inner elements -->
            <div class="{prefix}-titles">
```

| Tier | Class pattern | Example |
|---|---|---|
| **Section wrapper** | `{blockname}` — use the block name directly if it is unique | `banner`, `who-we-serve` |
| **Inner container** | `.container` (global utility) | `<div class="container">` |
| **Header / layout elements** | `{prefix}-{element}` (flat, no BEM) | `wws-header`, `wws-titles`, `wws-heading` |
| **Sub-component elements** | `{prefix}-{component}-*` (flat, single hyphen) | `wws-card`, `wws-card-image`, `wws-card-title` |

**Rules:**
- **Section wrapper** — use the block name directly (e.g. `banner`) when the name is unique and won't collide with any global or third-party class. Add the `comp-` prefix (e.g. `comp-who-we-serve`) only when the bare name is generic or ambiguous.
- The section wrapper scopes all descendent styles — nest everything inside it in SCSS.
- **SCSS Nesting** — use the parent selector (`&`) for inner elements when the prefix matches the block name to keep code DRY.
- **Never** create a custom container div — always use `.container`.
- Choose a short 2–4 letter prefix per block (e.g. `bn` for "Banner", `wws` for "Who We Serve", or just use the block name like `faq` if it's short).
- Flat single-hyphen `{prefix}-{element}` throughout — for both layout parts and repeating sub-components.
- **Never** use BEM double-underscore (`__`) notation.
- **Units in functions** — Always include the `px` unit when using the `rem()` function (e.g., `rem(32px)` instead of `rem(32)`).
- **No Hardcoded Colors** — Never use hardcoded HEX, RGB, or named colors in component files (e.g., `color: #1a1a1a;`). Always use variables from `abstracts/_variables.scss` (like `$color-primary`, `$text-dark`). If a color doesn't exist, add it as a new variable token first.
- Always use or add a token in `abstracts/_variables.scss`.
- **Media Queries** — Always use the `respond-above`, `respond-below`, or `respond-between` mixins from `_mixins.scss` for responsive breakpoints (e.g., `@include respond-above(md) { ... }`). Do not manually write `@media (min-width: ...)` with map-get.
- **Mixins for Repeated CSS** — If you use a CSS pattern repeatedly, abstract it into a mixin. Create any required new mixins in `abstracts/_mixins.scss`.

### Other utilities
- Background/text color classes auto-generated from `$bgcolor-map`: `.bg-white`, `.text-dark`
- Spacing utilities from `$spaceamounts`: `.mt-30`, `.pb-30`

## Adding New Files
| What | Where | Then |
|---|---|---|
| Component | `sources/scss/components/_name.scss` | `@use` in `components/_core.scss` |
| Layout section | `sources/scss/layout/_name.scss` | `@use` in `layout/_core.scss` |
| Template | `sources/scss/templates/_name.scss` | `@use` in `templates/_core.scss` |
