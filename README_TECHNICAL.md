# WPNest Technical Documentation

## 1. Project Overview
**WPNest** is a professional, high-performance WordPress starter theme designed for scalability and modern development workflows. It bridges the gap between traditional WordPress development and modern frontend tooling, providing a robust foundation for building bespoke themes.

Key objectives of the theme:
- **Performance**: Highly optimized asset delivery.
- **Maintainability**: Modular PHP and SCSS architecture.
- **Developer Experience**: Modern JS/CSS tooling with automated linting and bundling.

## 2. Tech Stack
The theme utilizes a modern stack to ensure performance and reliability:

- **CMS**: WordPress 6.x+
- **Frontend Core**: ES6+ JavaScript, Dart Sass (SCSS)
- **Tooling/Bundling**: Webpack 5, Babel, PostCSS, PurgeCSS
- **Dependency Management**: NPM (Node.js 20+), Composer
- **PHP Standards**: PHP Code Sniffer (PHPCS) with PSR-12/WordPress standards
- **JS/CSS Standards**: ESLint, Stylelint
- **Libraries**:
    - [Swiper](https://swiperjs.com/) (Slider)
    - [Fancybox](https://fancyapps.com/fancybox/) (Lightbox)
    - [jQuery](https://jquery.com/) (Available for legacy/library support)

---

## 3. Project Structure
The theme follows a clean hierarchy to separate source code from compiled assets:

```text
wp-nest/
├── assets/             # Compiled production assets (CSS, JS, Images)
├── config/             # Configuration files for ESLint, Stylelint, etc.
├── includes/           # Backend logic (Actions, Filters, Security, Scripts)
├── sources/            # Frontend source files
│   ├── js/             # Modular JavaScript
│   ├── scss/           # Modular SCSS (7-1 pattern approach)
│   └── images/         # Source images
├── template-parts/     # Reusable theme fragments
├── templates/          # WordPress page templates
├── webpack.config.js   # Build configuration
└── functions.php       # Theme entry point and initialization
```

---

## 4. Backend Architecture (PHP/WordPress)
WPNest uses a modular backend structure to avoid a bloated `functions.php`. Each file in `includes/` has a specific responsibility.

### [theme-scripts.php](file:///includes/theme-scripts.php)
Manages the enqueuing of assets using a manifest-based system.
- `themeManifest()`: Loads and caches the `manifest.json` for asset versioning.
- `themeCSS($key, $deps, $media)`: Helper to enqueue CSS files from the manifest.
- `themeJS($key, $deps, $in_footer)`: Helper to enqueue JS files from the manifest.
- `wpnestScripts()`: Hooks into `wp_enqueue_scripts` to load all theme assets and localizes AJAX variables.

### [theme-action.php](file:///includes/theme-action.php)
Contains custom WordPress actions and AJAX handlers.
- `disable_wp_emojis()`: Removes default WordPress emoji scripts to improve performance.
- `wpnest_ajaxsearch_filter()`: The server-side handler for AJAX-based post filtering and search. Includes nonce verification and pagination support.

### [theme-filter.php](file:///includes/theme-filter.php)
Houses custom WordPress filters.
- `allow_svg_upload($mimes)`: Extends allowed upload types to include SVG.
- `fix_svg_filetype_check($data, $file, $filename, $mimes)`: Ensures correct MIME type detection for SVG files.

### [theme-functions.php](file:///includes/theme-functions.php)
Provides utility functions for use throughout the theme templates.
- `wpnest_edit_post_link()`: Renders an "Edit" link for logged-in users.
- `placeholder_image($title)`: Returns a standard placeholder image if a featured image is missing.
- `acf_svg($attachment_id)`: Safely retrieves and returns the raw SVG code from a media attachment.
- `entry_banner()`: A versatile function that renders the hero section/banner for pages, posts, archives, and search results.
- `acf_link($link, $link_class)`: Transforms an ACF link array into a fully accessible HTML anchor tag.
- `acf_image($image_id, $size, $class)`: Renders an optimized `<img>` tag with `srcset` and lazy loading support from an ACF image ID.
- `trim_excerpt($text, $limit)`: Utility to truncate text to a specific word count.
- `wpnest_archive_post()`: A complex function that renders the entire blog archive UI, including category filters and the AJAX-ready post list.

### [theme-security.php](file:///includes/theme-security.php)
Hardens the WordPress installation.
- **REST API Restriction**: Limits REST API access and hides the `/users` endpoint from unauthenticated users.
- `disable_author_archive()`: Redirects all author archive requests to the homepage to prevent user enumeration.

### [acf-block-register.php](file:///includes/acf-block-register.php)
Streamlines the registration and rendering of Gutenberg blocks.
- `register_acf_blocks()`: Registers custom blocks via ACF.
- `theme_acf_block_render_callback($block)`: A global callback that dynamically loads the appropriate PHP template for a block from `template-parts/blocks/`.

---

## 5. Frontend Architecture
The frontend is built for performance and modularity.

### Webpack 5 Bundling
- **Dynamic Imports**: Used for splitting code and loading heavy libraries like Swiper and Fancybox only when needed (see `sources/js/script.js`).
- **Asset Manifest**: Generates a `manifest.json` in the `assets` folder, allowing PHP to load the correct hashed files.
- **PurgeCSS**: Automatically removes unused CSS by scanning `.php` and `.js` files during production builds.

### SCSS Structure
The theme uses a modular SCSS approach:
- **Abstracts**: Variables, Mixins, and Functions.
- **Base**: Reset, Typography, and Global styles.
- **Components**: UI-specific styles (Buttons, Cards).
- **Layout**: Header, Footer, and Grid styles.

---

## 6. Development Workflow

### Installation
1. `npm install` - Install JS dependencies and setup Husky.
2. `composer install` - Install PHP dependencies.

### Key Commands
- `npm run watch`: Local development with hot reloading/watching.
- `npm run build`: Production build (minified, purged, optimized).
- `npm run lint`: Runs all linters (JS, SCSS, PHP).

### Code Quality
- **Husky & lint-staged**: Automatically runs linters before every commit.
- **PHPCS**: Ensures compliance with PSR-12 and WordPress VIP coding standards.
