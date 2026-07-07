---
name: create-acf-block
description: Automatically scaffolds a new ACF Block by creating the PHP template, SCSS file, updating the ACF JSON, and registering the block in PHP.
---

# ACF Block Creation Workflow

Use this skill whenever the user asks to "create a new block" or "scaffold an ACF block".

Follow this strict 4-step process to generate the block correctly. **Never skip a step.**

## 1. Register the Block (PHP)

Modify `includes/acf-block-register.php`.
Find the `register_acf_blocks()` function. Add a new array to the `$blocks` array using this structure:

```php
			array(
				'name'        => '{block_slug}',
				'title'       => __( '{Block Name}', TEXT_DOMAIN ),
				'description' => __( '{Block description}', TEXT_DOMAIN ),
				'category'    => 'wpnest-blocks',
				'icon'        => '{dashicon}', // e.g., 'format-image'
				'keywords'    => array( '{keyword1}', '{keyword2}' ),
				'example'     => array(
					'attributes' => array(
						'mode' => 'edit',
						'data' => array(
							// Sample data for preview mode
							'heading' => 'Sample Preview Heading',
						),
					),
				),
			),
```

## 2. Create the PHP Template

Create a new file at `template-parts/blocks/{block_slug}.php`.
Implement the template according to the theme's structure and escaping rules (e.g. `esc_html()`, `esc_url()`).

Template Structure:
```php
<?php
/**
 * Block Name: {Block Name}
 *
 * @package WPNest
 */

$block_classes = 'wp-block-' . str_replace( '_', '-', $block['name'] );
if ( ! empty( $block['className'] ) ) {
	$block_classes .= ' ' . $block['className'];
}
if ( ! empty( $block['align'] ) ) {
	$block_classes .= ' align' . $block['align'];
}
?>

<section class="<?php echo esc_attr( $block_classes ); ?>">
	<div class="container">
		<!-- Block Content Here -->
	</div>
</section>
```

## 3. Create the SCSS File

Create a new file at `sources/scss/components/_{block_slug}.scss`.
Write the SCSS for the block.

Then, modify `sources/scss/components/_core.scss` to import it:
```scss
@use '{block_slug}';
```

## 4. Create the ACF JSON Configuration

Create a new file at `includes/acf-json/group_{block_slug}.json`.
Generate a standard ACF JSON structure for the field group, making sure the `location` rule matches the block name.

```json
{
    "key": "group_{block_slug}",
    "title": "{Block Name}",
    "fields": [
        // Define fields based on user request
    ],
    "location": [
        [
            {
                "param": "block",
                "operator": "==",
                "value": "acf/{block_slug}"
            }
        ]
    ],
    "menu_order": 0,
    "position": "normal",
    "style": "default",
    "label_placement": "top",
    "instruction_placement": "label",
    "hide_on_screen": "",
    "active": true,
    "description": "",
    "show_in_rest": 0
}
```

## Completion

Once all 4 steps are complete, inform the user that the block has been perfectly scaffolded according to the WPNest starter kit standards.
