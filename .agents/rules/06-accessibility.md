---
trigger: manual
---

# Accessibility — WCAG 2.1 AA — SITE_NAME

## Perceivable

- **Images:** `<img alt>` · meaningful → descriptive · decorative → `alt=""` + `aria-hidden` · icons/SVG: `aria-hidden` + visible or `.screen-reader-text` label
- **Contrast:** text 4.5:1 · large text (≥18px / ≥14px bold) 3:1 · UI/focus 3:1 · adjust bg/weight, never lighten text
- **Reflow:** readable at 200% · no horizontal scroll at 320px · use `rem()` · avoid `overflow:hidden` on text
- **Colour:** links distinguishable beyond colour · errors/required states need non-colour indicator
- **Text spacing (1.4.12):** survive user overrides · unitless `line-height: 1.5`

## Operable

- **Keyboard:** all interactive keyboard-operable · logical tab order · no `tabindex>0` · Esc leaves modals/flyouts
- **Skip link:** first focusable in `header.php`: `.skip-link.screen-reader-text` → `#content` · `<main id="content" class="main-content">` · visible on `:focus`
- **Focus:** never `outline:none` without replacement · use `:focus-visible` · `outline: 3px solid $color-accent; outline-offset: 3px`
- **Links:** purpose clear out of context — no bare "click here"/"read more" · `aria-label` or `.screen-reader-text` if short text

## Understandable

- **Language:** `<html <?php language_attributes(); ?>>` — never hardcode `lang`
- **Headings:** one `<h1>`/page · blocks start `<h2>` · no skipped levels for styling
- **Forms:** every input has `<label>` (`for`/`id` or `aria-label`) · placeholder ≠ label · required: `required` attr + visual mark + sr-only "(required)"
- **Errors:** identify in text not colour · `aria-describedby` · focus first error on submit

## Robust (landmarks & ARIA)

Landmarks: `<header>` banner · `<nav aria-label>` if multiple · `<main>` · `<footer>` contentinfo · `<section aria-labelledby>`.

| Widget    | Required ARIA                                             |
| --------- | --------------------------------------------------------- |
| Accordion | `aria-expanded` `aria-controls` panel-id                  |
| Tabs      | `tablist` `tab` `tabpanel` `aria-selected`                |
| Modal     | `role=dialog` `aria-modal` `aria-labelledby` + focus trap |
| Alert     | `role=alert` or `aria-live=polite`                        |
| Toggle    | `aria-pressed`                                            |

Dynamic updates: `aria-live=polite` screen-reader region · update `textContent` on change.

## Never

`outline:none` without replacement · `<div>`/`<span>` interactive without role+keyboard · `tabindex>0` · colour-only meaning · placeholder as label.

`.screen-reader-text` in `base/_wordpress.scss` · `:focus` restores visible layout. Manual keyboard + screen reader test before delivery.
