---
trigger: always_on
---

# WPNest — ACF Blocks Rules

## How It Works

All blocks use a **single shared render callback** (`theme_acf_block_render_callback`).

The callback automatically strips the `acf/` prefix from the block name and loads the matching template.

```text
Block name: acf/my_block
Template:   template-parts/blocks/my_block.php
```

If the template file does not exist, display a visible error in the editor to make debugging easier.

---

## Template Structure

Every block template must begin with:

```php
<?php
if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	exit;
}
```

---

## Block Wrapper (ID & Class)

Every block must support custom **Anchor** and **Additional CSS Class** values.

```php
// Support custom anchor values.
$block_id = '{block-prefix}-' . $block['id'];

if ( ! empty( $block['anchor'] ) ) {
	$block_id = $block['anchor'];
}

// Support custom class names.
$class_name = '{block-name}';

if ( ! empty( $block['className'] ) ) {
	$class_name .= ' ' . $block['className'];
}
```

Use them on the outer wrapper.

```php
<section
	id="<?php echo esc_attr( $block_id ); ?>"
	class="<?php echo esc_attr( $class_name ); ?>"
>
```

---

## File Structure

| Purpose | Location |
|----------|----------|
| Block Registration | `includes/acf-block-register.php` |
| Shared Render Callback | `theme_acf_block_render_callback` |
| Block Templates | `template-parts/blocks/{block-name}.php` |
| ACF JSON | `includes/acf-json/` |
| Block SCSS | `sources/scss/components/_comp-{block-name}.scss` |
| Block JavaScript | `sources/js/modules/{block-name}.js` |

---

# Template Rules

Every block should follow this structure:

1. Security check.
2. Retrieve fields.
3. Return early when empty.
4. Prepare wrapper variables.
5. Render only existing content.
6. Escape all output.

Never mix rendering and business logic.

---

# Content Rules

## Never Output Empty HTML

Always return immediately if there is nothing to display.

```php
if ( empty( $heading ) && empty( $items ) ) {
	return;
}
```

---

## Repeaters

Always verify repeater rows exist.

```php
if ( have_rows( 'items' ) ) {

	while ( have_rows( 'items' ) ) {
		the_row();

		$item = get_row();

		if ( empty( $item ) ) {
			continue;
		}
	}
}
```

Never assume rows exist.

---

## Individual Fields

Always validate before rendering.

```php
if ( $heading ) { ... }

if ( $image_id ) { ... }

if ( ! empty( $link['url'] ) ) { ... }
```

Never output placeholder HTML.

---

## Wrapper Elements

Only render parent wrappers if at least one child element will render.

Correct:

```php
<?php if ( $heading || $description ) : ?>

<section>

	...

</section>

<?php endif; ?>
```

Incorrect:

```php
<section></section>
```

---

## Escaping

Always escape output appropriately.

| Content | Escape Function |
|----------|-----------------|
| Text | `esc_html()` |
| HTML / WYSIWYG | `wp_kses_post()` |
| URL | `esc_url()` |
| Attribute | `esc_attr()` |

Never:

- `echo get_field();`
- `<img>` using attachment ID `0`
- `href="#"` placeholders
- Empty wrappers
- Raw HTML from ACF

---

# Adding a New Block

## 1. Register the Block

Register the block inside:

```
includes/acf-block-register.php
```

Always use the shared render callback.

Never create a new render callback.

---

## 2. Create the Template

Create:

```
template-parts/blocks/{block-name}.php
```

---

## 3. Create the Field Group

In WordPress Admin:

```
Custom Fields
```

Assign the Location Rule to the block.

---

## 4. Verify ACF JSON (Required)

After creating or updating a field group:

1. Save the field group.
2. Confirm a JSON file is created or updated inside:

```
includes/acf-json/
```

3. Commit the JSON file to the repository.

If the JSON file is **not** generated:

- Verify the `acf/settings/save_json` filter is configured correctly.
- Ensure the `includes/acf-json/` directory exists.
- Ensure the directory is writable.
- Save the field group again.
- Verify the JSON file is generated before considering the task complete.

**Never consider an ACF Block complete until its JSON file exists.**

---

# Block SCSS

Create one SCSS file per block.

```
sources/scss/components/
    _comp-{block-name}.scss
```

Import it into:

```
sources/scss/components/_core.scss
```

Never place block styles in unrelated files.

---

# Block JavaScript

Create one JavaScript module per block.

```
sources/js/modules/{block-name}.js
```

Export a named initializer.

Lazy-load the module only when the block exists.

Example:

```javascript
if (document.querySelector('.my-block')) {
	import('./modules/my-block.js').then(({ initMyBlock }) => {
		initMyBlock();
	});
}
```

---

# JavaScript Libraries

When using libraries such as:

- Swiper
- Fancybox
- GSAP
- Splide
- Lenis
- AOS
- etc.

Follow these rules:

- Import only when the block exists.
- Initialize only inside the current block.
- Never initialize globally.
- Never query the entire document when block scoping is possible.

Correct:

```javascript
blocks.forEach((block) => {
	const sliders = block.querySelectorAll('.swiper');

	...
});
```

Incorrect:

```javascript
document.querySelectorAll('.swiper');
```

---
