---
trigger: always_on
---

# WPNest — JavaScript Rules


## Path Aliases (use in all imports)
```js
@js    → sources/js/
@scss  → sources/scss/
```

## Entry Point Pattern — script.js
```js
// EAGER — global UI only
import deviceMenu from '@js/common/deviceMenu.js';
deviceMenu();

// LAZY — guard with DOM check before importing
if (document.querySelector('.home-slider')) {
    import('@js/common/swiperInit.js').then(({ initHomeSlider }) => initHomeSlider());
}

if (document.querySelector('[data-fancybox]')) {
    import('@js/common/fancybox').then(({ initFancybox }) => initFancybox());
}
```
**Rule:** Eager = global UI only (nav, menu). Everything else = conditional dynamic import.

## Module Pattern — named exports, single responsibility
```js
// sources/js/common/myFeature.js
export function initMyFeature() {
    // all DOM access inside init — safe for lazy loading
}
```

## Rules
- `const` / `let` only — never `var`
- Named exports — no default exports on modules (only on entry-level components)
- No inline `<script>` in PHP templates — all JS in `sources/js/`
- New module → lazy import in `script.js` with DOM guard
- New AJAX action → add handler in `sources/js/modules/ajax-scripts.js`
- Third-party libraries: Always use **Swiper** for carousels/sliders.
- **Swiper Scoping**: When using Swiper in a block, always scope its initialization to that specific block's wrapper (e.g., `block.querySelectorAll('.swiper')`) rather than globally, and ensure it is conditionally lazy-loaded in `script.js` only when the block exists.
- Third-party libraries: Always use **Fancybox** for popups, modals, and galleries.
