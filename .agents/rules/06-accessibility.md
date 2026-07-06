---
trigger: manual
---

# Accessibility — WCAG 2.1 AA

## Perceivable

- Images need meaningful `alt` (decorative → `alt=""` + `aria-hidden`).
- Maintain WCAG contrast ratios.
- Support 200% zoom and 320px width without horizontal scroll.
- Don't rely on color alone.
- Use unitless `line-height: 1.5`.

## Operable

- All controls must be keyboard accessible.
- Add a skip link to `#content`.
- Never remove focus outlines; use `:focus-visible`.
- Links should be descriptive.

## Understandable

- Use `language_attributes()`.
- One `<h1>` per page; blocks start with `<h2>`.
- Every form field needs a label.
- Show accessible error messages.

## Robust

- Use semantic landmarks (`header`, `nav`, `main`, `footer`).
- Add required ARIA for accordions, tabs, modals, alerts, and toggles.
- Use `aria-live` for dynamic content.

## Never

- `outline: none`
- `tabindex > 0`
- Interactive `<div>`/`<span>` without keyboard support
- Placeholder as a label
- Color-only indicators

**Always:** Include `.screen-reader-text` and perform keyboard + screen reader testing before delivery.
