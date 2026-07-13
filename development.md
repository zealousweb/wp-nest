# WPNest Theme Development Guide

Welcome to the **WPNest Theme** development documentation. This guide details the theme architecture, codebase structure, development workflow, coding standards, and best practices.

---

## 📖 Table of Contents
1. [Overview & Tech Stack](#1-overview--tech-stack)
2. [Getting Started & Installation](#2-getting-started--installation)
3. [Theme Rebranding & Renaming](#3-theme-rebranding--renaming)
4. [Complete Directory & File Index](#4-complete-directory--file-index)
5. [Toolchain & Command Reference](#5-toolchain--command-reference)
6. [Asset Pipeline & Enqueues (Vite 8)](#6-asset-pipeline--enqueues-vite-8)
7. [ACF Gutenberg Blocks Architecture](#7-acf-gutenberg-blocks-architecture)
8. [JavaScript System & Core Modules](#8-javascript-system--core-modules)
9. [SCSS Style Guidelines & Architecture](#9-scss-style-guidelines--architecture)
10. [PHP Hook & Utility Architecture](#10-php-hook--utility-architecture)
11. [Security Hardening & Privacy](#11-security-hardening--privacy)
12. [CI/CD Automated Pipelines](#12-cicd-automated-pipelines)

---

## 1. Overview & Tech Stack

**WPNest** is a WordPress starter theme built for speed, stability, and maintainability. It employs modern developer tooling to compile frontend assets and enforce coding standards:
- **Core**: HTML5 semantic markup and modular PHP.
- **Asset Compiler**: [Vite 8](https://vite.dev) for instant Hot Module Replacement (HMR) and production bundling.
- **CSS Preprocessor**: [Dart Sass](https://sass-lang.com) featuring modular `@use` / `@forward` architecture.
- **CSS Post-processing**: [PostCSS](https://postcss.org) running [Autoprefixer](https://github.com/postcss/autoprefixer), [PurgeCSS](https://purgecss.com) (production-only class scanning), and [cssnano](https://cssnano.co) (minification).
- **Quality Assurance**:
  - **ESLint 10** (Flat Config) for JavaScript linting.
  - **Stylelint 17** for SCSS file formatting.
  - **PHP_CodeSniffer (PHPCS)** using the **WordPress Coding Standards (WPCS)** ruleset.
  - **Husky** and **lint-staged** for automated pre-commit validation.

---

## 2. Getting Started & Installation

### Prerequisites
Ensure your local environment meets these requirements:
- **Node.js**: `v23.11.1` (specified in `package.json` engines)
- **Composer**: For PHP package management and WPCS testing
- **Local WP / Valet**: A running WordPress local host
- **ACF Pro**: The Advanced Custom Fields PRO plugin installed and active

### Installation Steps
1. Clone the repository into your WordPress `wp-content/themes/` folder:
   ```bash
   git clone https://github.com/zealousweb/wp-nest.git your-theme-folder
   cd your-theme-folder
   ```
2. Install npm and PHP dependencies:
   ```bash
   npm install
   composer install
   ```
3. Copy the environment template file:
   ```bash
   cp .env.local .env
   ```
4. Update the `.env` file with your local URL:
   ```env
   VITE_DEV_SERVER = http://your-local-site.local
   VITE_DEV_SERVER_PORT = 5173
   VITE_DEV_THEME_NAME = your-theme-folder
   ```

---

## 3. Theme Rebranding & Renaming

To rebrand the theme and prevent namespace or translation domain collisions, update the following configurations in your project:

| # | File Path | Scope | Target Change |
|---|---|---|---|
| **1** | [functions.php](/wp-local/app/public/wp-content/themes/wpnest/functions.php) (Lines 21-22) | PHP Constants | Change `'wpnest'` to your custom identifier in `THEME_PREFIX` and `TEXT_DOMAIN` constants. |
| **2** | [style.css](/wp-local/app/public/wp-content/themes/wpnest/style.css) (Line 12) | Theme Metadata | Update the `Text Domain: wpnest` field and metadata headers. |
| **3** | [.env](/wp-local/app/public/wp-content/themes/wpnest/.env) (Line 4) | Build Config | Update `VITE_DEV_THEME_NAME = wp-nest` to match your theme directory folder name. |
| **4** | Theme PHP Files | Global Search | Find and replace the `wpnest_` function prefix (e.g. `wpnest_setup()` → `mytheme_setup()`). |

---

## 4. Complete Directory & File Index

Below is the directory mapping of the entire theme, listing every file and its exact purpose:

```text
├── .cursor/                         # Agent rule configurations
│   └── rules/                       # Specific workflow rules (01-10)
├── .github/                         # GitHub integrations
│   └── workflows/                   # Automated GitHub Actions
│       ├── code-standards.yml       # Lints PHP, SCSS, JS and builds code on push
│       ├── pr-check.yml             # Validates PR titles and warns on huge changes
│       └── release.yml              # Packs production builds into a ZIP on tags
├── .husky/                          # Git hooks config (pre-commit trigger)
├── assets/                          # Vite build outputs (Minified, auto-hashed. DO NOT TOUCH)
├── config/                          # Toolchain and lint configurations
│   ├── config.eslint.js             # ESLint 10 Flat Configuration file
│   ├── config.stylelint.js          # Stylelint 17 configuration rules
│   ├── postcss.config.js            # PostCSS configuration with PurgeCSS & Autoprefixer
│   └── purgecss-safelist.js         # Class selectors exempt from PurgeCSS tree-shaking
├── includes/                        # PHP includes and core functional code
│   ├── acf-block-preview/           # Folder hosting PNG/JPG preview screenshots for Gutenberg blocks
│   ├── acf-json/                    # ACF field group definitions (Auto-generated JSON files)
│   ├── acf-block-register.php       # Handles Custom Gutenberg category and block registrations
│   ├── theme-action.php             # Action hooks, emoji removals, and custom AJAX handlers
│   ├── theme-cpt.php                # Blueprint for custom post types and custom taxonomies
│   ├── theme-filter.php             # Filters, head tag cleanups, and SVG mime uploads
│   ├── theme-functions.php          # PHP utilities (acf_link, acf_image, placeholders, excerpt filters)
│   ├── theme-scripts.php            # Vite enqueuing, client socket integration, and script loaders
│   └── theme-security.php           # Security rules, REST endpoint restrictions, XML-RPC disablers
├── sources/                         # Raw development assets
│   ├── images/                      # Images, logos, and system assets
│   ├── js/                          # Javascript source files
│   │   ├── common/                  # Helper/library JS scripts (Swiper initialization, fancybox)
│   │   │   ├── deviceMenu.js        # Dynamic mobile navigation slide drawer
│   │   │   ├── fancybox.js          # Simple fancybox setup module
│   │   │   └── swiperInit.js        # Swiper component initialization module
│   │   ├── modules/                 # Page/feature specific scripts
│   │   │   └── ajax-scripts.js      # AJAX category search/pagination listener
│   │   └── script.js                # Primary Javascript entry file (Eager & dynamic import calls)
│   └── scss/                        # Stylesheets sources (Dart Sass layout)
│       ├── abstracts/               # SCSS helper configurations
│       │   ├── _function.scss       # Pixel-to-rem calculator and core utilities
│       │   ├── _mixins.scss         # Breakpoint, grid, and layout mixins
│       │   └── _variables.scss      # Theme configuration variables (fonts, colors, sizes)
│       ├── base/                    # Core foundation styles
│       │   ├── _buttons.scss        # Button sizes and states
│       │   ├── _core.scss           # Base entry point (imports fonts, typography, normalize, reset)
│       │   ├── _fonts.scss          # External font-family loaders
│       │   ├── _forms.scss          # Inputs, checkboxes, textareas
│       │   ├── _normalize.scss      # Modern CSS resets
│       │   ├── _typography.scss     # Body, headings, line heights
│       │   ├── _utilities.scss      # Margin, padding, utility maps
│       │   └── _wordpress.scss      # Standard WordPress classes (alignleft, wp-caption)
│       ├── components/              # Block-level component styles
│       │   ├── _core.scss           # Component index (imports _hero-banner)
│       │   └── _hero-banner.scss    # Hero banner block layout
│       ├── layout/                  # Main structure components
│       │   ├── _core.scss           # Layout index
│       │   ├── _default.scss        # Main page container and wrappers
│       │   ├── _device-menu.scss    # Desktop & mobile navigation drawer styles
│       │   ├── _footer.scss         # Main footer styling variables
│       │   └── _header.scss         # Header nav list styles
│       ├── templates/               # Custom page layout styles
│       │   ├── _comp-hero.scss      # Custom template components
│       │   └── _core.scss           # Page template index
│       └── style.scss               # Main stylesheet compiling entry file
├── template-parts/                  # PHP templates and partials
│   └── blocks/                      # ACF Gutenberg block template PHP renders
│       ├── custom-block.php         # Custom ACF block template example
│       └── wysiwyg.php              # Standard WYSIWYG text area block
├── templates/                       # Page-specific WordPress templates
│   └── frontpage.php                # Front page template
├── .editorconfig                    # Editor indentation and line break rules
├── .env.local                       # Local environment base variables template
├── .gitignore                       # Git ignore list
├── .phpcs.xml                       # PHP_CodeSniffer configuration (WPCS standards overrides)
├── 404.php                          # 404 template layout
├── archive.php                      # Main category/archive template
├── comments.php                     # Standard comments template
├── composer.json                    # Composer requirements (PHPCS, WPCS, PHPUnit)
├── footer.php                       # Footer template wrapper
├── functions.php                    # WordPress bootstrap file (requires includes)
├── index.php                        # Default fallback template
├── package.json                     # NodeJS project script runner and dependencies
├── page.php                         # Core page template layout
├── phpunit.xml.dist                 # PHPUnit test setup template
├── screenshot.png                   # WordPress dashboard theme screenshot
├── single.php                       # Single post template layout
├── theme.json                       # Gutenberg theme settings and palette configuration
└── vite.config.js                   # Vite configuration (manifests, optimization, and Rollup inputs)
```

### Directory Use Matrix

To keep the theme structured, perform actions within directories according to the following usage matrix:

| Directory | Target File Types | Lint Standard | Dev Actions Allowed | Prohibited / Restricted Actions |
|---|---|---|---|---|
| **Root (Theme Root)** | `.php`, `.css`, `.json` | PHPCS (WPCS) | Edit page templates (e.g., `page.php`, `single.php`, `archive.php`). | **Do NOT** write helper functions or business logic here. Keep code thin. Bootstrap in `functions.php` only. |
| **`includes/`** | `.php` | PHPCS (WPCS) | Add/modify theme setup hooks, custom action triggers, AJAX logic, CPTs, and helper functions. | **Do NOT** output raw HTML or inline CSS/scripts. Keep logic purely functional. |
| **`includes/acf-json/`** | `.json` | None | Automatically write and load field groups via ACF plugin interface. | **Do NOT** manually modify files in this directory. Always sync changes through the WP Admin ACF panel. |
| **`includes/acf-block-preview/`** | `.jpg`, `.png` | None | Add Gutenberg editor preview screenshots of custom blocks. | Filename **must** match the block registration slug exactly (e.g., `custom-block.png`). Keep file sizes small. |
| **`template-parts/blocks/`** | `.php` | PHPCS (WPCS) | Add/modify PHP rendering layouts for custom ACF blocks. | **Do NOT** register field groups or styles here. Keep wrapper elements clean using anchor and class attributes. |
| **`sources/js/`** | `.js` | ESLint 10 | Write global eager scripts or feature-specific dynamic JS modules. | **Never** use `var` variables. Ensure dynamic module imports are properly checks-wrapped before loading. |
| **`sources/scss/`** | `.scss` | Stylelint 17 | Define abstracts, theme styling rules, custom block styles, and media queries. | **Do NOT** hardcode color values. Never use BEM nesting (`__` or `--`). Use modular `@use` instead of `@import`. |
| **`assets/`** | `.js`, `.css`, `.json` | None | Target path for production bundles and development HMR scripts. | **NEVER** edit files in this folder manually. Any custom changes will be deleted on the next `npm run build`. |
| **`config/`** | `.js` | ESLint 10 | Modify toolchain configuration rules, PurgeCSS safelists, and PostCSS configurations. | Ensure rules matches does not break the GitHub runner environments or husky pre-commits. |

---


## 5. Toolchain & Command Reference

Manage dependencies and run linting/compiling processes using `package.json` scripts:

```bash
npm run dev          # Spin up local Vite HMR server
npm run build        # Build minified assets for production

npm run lint         # Check JS + SCSS code quality
npm run lint:fix     # Automatically fix JS + SCSS formatting issues

npm run phpcs        # Validate PHP files against WordPress coding standards
npm run phpcbf       # Automatically format PHP scripts (WPCS)
```

---

## 6. Asset Pipeline & Enqueues (Vite 8)

Frontend compilation uses **Vite 8** to build JavaScript modules, compile Dart Sass, optimize images, and output cache-busting files.

### Development Mode
During development, the theme loads files directly from the Vite memory server (default `http://127.0.0.1:5173`):
1. [theme-scripts.php](/wp-local/app/public/wp-content/themes/wpnest/includes/theme-scripts.php) initializes a connection request with `viteDevServerUrl()`.
2. It enqueues the local Vite client (`/@vite/client`) to activate HMR.
3. Dev assets are loaded dynamically over a WebSocket.
4. Active filter hooks add `type="module"` to enqueued scripts.

### Production Mode
For production (`npm run build`), Vite creates a dependency manifest at `assets/.vite/manifest.json`:
1. It reads the entry points: `sources/js/script.js`, `sources/scss/style.scss`, and dynamically located scripts under `sources/js/modules/**/*.js`.
2. Assets are built, chunk-split, optimized, and saved under `assets/` with cache-busting hashes.
3. [theme-scripts.php](/wp-local/app/public/wp-content/themes/wpnest/includes/theme-scripts.php) parses `manifest.json` and loads the optimized file paths.

```mermaid
graph TD
    A[wp_enqueue_scripts] --> B{isViteDev?}
    B -- Yes --> C[Load /@vite/client]
    B -- Yes --> D[Load sources/js/script.js directly]
    B -- No  --> E[Load assets/.vite/manifest.json]
    E --> F[Map handles to hashed filenames]
    F --> G[Load assets/js/main-[hash].js]
```

---

## 7. ACF Gutenberg Blocks Architecture

Custom ACF blocks are registered programmatically and rendered using templates.

```
┌────────────────────────────────────────────────────────┐
│             ACF Gutenberg Block Workflow               │
├────────────────────────────────────────────────────────┤
│                                                        │
│  1. REGISTRATION                                       │
│     includes/acf-block-register.php                    │
│     (Append to $blocks array)                          │
│                                                        │
│  2. TEMPLATE RENDER                                    │
│     template-parts/blocks/{block}.php                  │
│     (Use $block['anchor'] and $block['className'])     │
│                                                        │
│  3. LAYOUT STYLE                                       │
│     sources/scss/components/_{block}.scss              │
│     (Import into sources/scss/components/_core.scss)   │
│                                                        │
│  4. FIELD CONFIGURATION                                │
│     Sync JSON: includes/acf-json/group_{block}.json    │
│                                                        │
└────────────────────────────────────────────────────────┘
```

### The Registration Process
Append a definition configuration to the `$blocks` array in [acf-block-register.php](/wp-local/app/public/wp-content/themes/wpnest/includes/acf-block-register.php):
```php
array(
    'name'        => 'my_block',
    'title'       => __( 'My Block', TEXT_DOMAIN ),
    'description' => __( 'A custom block.', TEXT_DOMAIN ),
    'category'    => 'wpnest-blocks',
    'icon'        => 'admin-page',
    'keywords'    => array( 'custom' ),
    'example'     => array(
        'attributes' => array(
            'mode' => 'preview',
            'data' => array(
                'heading' => 'Sample Heading',
            ),
        ),
    ),
)
```

### Rendering & Previews
1. **Single Callback**: Every block uses the `theme_acf_block_render_callback()` function.
2. **Preview Mode**: In the Gutenberg sidebar or blocks listing, the renderer detects `acf-block-preview/{block-slug}.png` or `.jpg` and shows it as a preview static image instead of rendering empty database fields.
3. **Database Syncing**: The theme binds the ACF load/save actions directly to the local directory `includes/acf-json/`.

---

## 8. JavaScript System & Core Modules

The Javascript architecture optimizes loading speeds by splitting execution:
- **Eager scripts**: Critical components loaded immediately (e.g. mobile navigation drawers).
- **Lazy scripts**: Async assets imported dynamically only if their matching HTML selectors exist on the page.

### The script.js Entry File
[script.js](/wp-local/app/public/wp-content/themes/wpnest/sources/js/script.js) controls global execution flow:
```javascript
// Device menu is global, so keep it eager
import deviceMenu from '@js/common/deviceMenu.js';
deviceMenu();

// Load Home slider only if homepage elements exist
if (document.querySelector('.home-slider')) {
    import('@js/common/swiperInit.js').then(({ initHomeSlider }) => {
        initHomeSlider();
    });
}
```

### Key Modules
- **deviceMenu.js**: Configures drawer navigation behavior, caret placement, offsets based on header heights, and sub-menu navigation slide-backs.
- **ajax-scripts.js**: Implements an AJAX search filter. Binds listeners to categories (`#postcategory`) and pagination links, fetching filtered posts with `fetch()` and nonces, then dispatching the `wpnest:ajax_updated` event.

---

## 9. SCSS Style Guidelines & Architecture

Stylesheets are compiled using Sass modular variables, responsive mixins, and class hierarchies.

### SCSS Variables & Mixins
- Variable definitions exist in [abstracts/_variables.scss](/wp-local/app/public/wp-content/themes/wpnest/sources/scss/abstracts/_variables.scss) (e.g. theme colors, typography fonts, margins).
- Use the `rem(pxValue)` function in abstracts to write responsive size declarations.
- Breakpoints use media-query mixins from [abstracts/_mixins.scss](/wp-local/app/public/wp-content/themes/wpnest/sources/scss/abstracts/_mixins.scss):
  ```scss
  .my-container {
      width: 100%;
      @include respond-above(md) {
          width: 75%;
      }
  }
  ```

### Class Selector Formatting
1. Section wrappers should match the block name (e.g., `.custom-block`).
2. Do not use BEM syntax (`__` or `--`). Instead, nest children inside parent selectors:
   ```scss
   .custom-block {
       .cb-title {
           font-size: rem(24px);
       }
       .cb-content {
           margin-top: rem(10px);
       }
   }
   ```

---

## 10. PHP Hook & Utility Architecture

Business logic is organized into module files in `includes/`:

### theme-functions.php
Houses utility helper functions:
- `placeholder_image()`: Generates fallback images.
- `acf_svg( $attachment_id )`: Returns the raw SVG code from safe local files.
- `entry_banner()`: Renders page, tag, search, or 404 header banner elements.
- `acf_link( $link, $css_class )`: Generates accessible link elements from ACF arrays.
- `acf_image( $image_id, $size, $class )`: Generates optimized inline image tags.

### theme-action.php & theme-filter.php
- **theme-action.php**: Manages custom action hooks. Handles emoji removals and binds the AJAX `wpnest_ajaxsearch_filter` callback.
- **theme-filter.php**: Sanitizes and allows SVG uploads, and runs `wpnest_head_cleanup()` to remove default WordPress bloat:
  ```php
  remove_action( 'wp_head', 'wp_generator' );
  remove_action( 'wp_head', 'rsd_link' );
  remove_action( 'wp_head', 'wlwmanifest_link' );
  ```

### theme-cpt.php
Provides templates for registering custom post types and taxonomies:
```php
function wpnest_register_custom_post_types() {
    // Custom post type registration goes here
}
add_action( 'init', 'wpnest_register_custom_post_types' );
```

---

## 11. Security Hardening & Privacy

To improve WordPress privacy and block security vulnerabilities, [theme-security.php](/wp-local/app/public/wp-content/themes/wpnest/includes/theme-security.php) applies the following filters:

- **Disable Author Archives**: Checks if the query targets an author page and redirects the visitor to the homepage to prevent user enumeration.
- **REST User Endpoint Filtering**: Disables access to `/wp/v2/users` for unauthenticated requests.
- **XML-RPC Protection**: Disables XML-RPC interface protocols (`xmlrpc_enabled` returning `false`) to prevent brute-force attacks.
- **Attachment Comment Restrictions**: Automatically closes comments on all attachment files to block media page spam.
- **Guards**: Every PHP template starts with an `ABSPATH` guard to prevent direct execution:
  ```php
  if ( ! defined( 'ABSPATH' ) ) {
      exit;
  }
  ```

---

## 12. CI/CD Automated Pipelines

Automated integration runs via three GitHub Actions workflows in `.github/workflows/`:

1. **Code Standards Checks (`code-standards.yml`)**:
   Runs when pushing code to `feature/*`, `stage`, or `master`. It checks JS lint rules, SCSS formatting rules, and PHPCS styling rules, then runs `npm run build` to verify compiling integrity.
2. **PR Checks (`pr-check.yml`)**:
   Runs when creating PR requests to stable target branches. It checks pull request title lengths and warns if more than 50 files are modified in a single merge request.
3. **Draft Release Auto-Pack (`release.yml`)**:
   Runs when pushing tags (e.g. `v1.0.3`). Installs all dependencies, compiles assets, strips out Git/node files, compiles a production ZIP, and uploads it to GitHub Releases.
