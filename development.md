# WPNest Theme Development Guide

Welcome to the **WPNest Theme** development documentation. This guide details the theme architecture, codebase structure, development workflow, coding standards, and best practices.

---

## 📖 Table of Contents

1. [Overview & Tech Stack](#1-overview--tech-stack)
2. [Getting Started & Installation](#2-getting-started--installation)
3. [Theme Rebranding & Renaming](#3-theme-rebranding--renaming)
4. [Complete Directory & File Index](#4-complete-directory--file-index)
5. [Toolchain & Command Reference](#5-toolchain--command-reference)
6. [Asset Pipeline & Enqueues (Vite 8 + Tailwind CSS v4)](#6-asset-pipeline--enqueues-vite-8--tailwind-css-v4)
7. [ACF Gutenberg Blocks Architecture](#7-acf-gutenberg-blocks-architecture)
8. [JavaScript System & Core Modules](#8-javascript-system--core-modules)
9. [Tailwind CSS v4 Guidelines & Architecture](#9-tailwind-css-v4-guidelines--architecture)
10. [PHP Hook & Utility Architecture](#10-php-hook--utility-architecture)
11. [Security Hardening & Privacy](#11-security-hardening--privacy)
12. [CI/CD Automated Pipelines](#12-cicd-automated-pipelines)

---

## 1. Overview & Tech Stack

**WPNest** is a WordPress starter theme built for speed, stability, and maintainability. It employs modern developer tooling to compile frontend assets and enforce coding standards:

- **Core**: HTML5 semantic markup and modular PHP.
- **Asset Compiler**: [Vite 8](https://vite.dev) with [`@tailwindcss/vite`](https://tailwindcss.com) for instant Hot Module Replacement (HMR) and production bundling.
- **CSS Framework**: **Tailwind CSS v4** featuring `@import "tailwindcss"`, `@source` content scanning, `@theme` design tokens, and `@utility` class declarations.
- **CSS Post-processing**: [Autoprefixer](https://github.com/postcss/autoprefixer) for vendor prefixing.
- **Quality Assurance**:
    - **ESLint 10** (Flat Config) for JavaScript linting.
    - **Stylelint 17** for CSS file formatting.
    - **PHP_CodeSniffer (PHPCS)** using the **WordPress Coding Standards (WPCS)** ruleset.
    - **Husky** and **lint-staged** for automated pre-commit validation.

---

## 2. Getting Started & Installation

### Prerequisites

Ensure your local environment meets these requirements:

- **Node.js**: `v24.15.0` (specified in `package.json` engines)
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

| #     | File Path                                    | Scope          | Target Change                                                                               |
| ----- | -------------------------------------------- | -------------- | ------------------------------------------------------------------------------------------- |
| **1** | [functions.php](functions.php) (Lines 34-35) | PHP Constants  | Change `'wpnest'` to your custom identifier in `THEME_PREFIX` and `TEXT_DOMAIN` constants.  |
| **2** | [style.css](style.css) (Line 12)             | Theme Metadata | Update the `Text Domain: wpnest` field and metadata headers.                                |
| **3** | [.env](.env) (Line 4)                        | Build Config   | Update `VITE_DEV_THEME_NAME = wp-nest` to match your theme directory folder name.           |
| **4** | Theme PHP Files                              | Global Search  | Find and replace the `wpnest_` function prefix (e.g. `wpnest_setup()` → `mytheme_setup()`). |

---

## 4. Complete Directory & File Index

Below is the directory mapping of the entire theme, listing every file and its exact purpose:

```text
├── .cursor/                         # Agent rule configurations
│   └── rules/                       # Specific workflow rules (01-10)
├── .github/                         # GitHub integrations
│   └── workflows/                   # Automated GitHub Actions
│       ├── code-standards.yml       # Lints PHP, CSS, JS and builds code on push
│       ├── pr-check.yml             # Validates PR titles and warns on huge changes
│       └── release.yml              # Packs production builds into a ZIP on tags
├── .husky/                          # Git hooks config (pre-commit trigger)
├── assets/                          # Vite build outputs (Minified, auto-hashed. DO NOT TOUCH)
├── config/                          # Toolchain and lint configurations
│   ├── config.eslint.js             # ESLint 10 Flat Configuration file
│   ├── config.stylelint.js          # Stylelint 17 configuration rules
│   └── postcss.config.js            # PostCSS configuration with Autoprefixer
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
│   └── css/                         # Stylesheet sources (Tailwind CSS v4)
│       ├── style.css                # Main stylesheet compiling entry file (@import "tailwindcss", @source, @theme, @utility)
│       └── menu.css                 # Navigation styles (desktop dropdowns, hamburger, mobile drawer)
├── template-parts/                  # PHP templates and partials
│   └── blocks/                      # ACF Gutenberg block templates (V2 block.json architecture)
│       ├── wysiwyg/                 # Standard WYSIWYG block (block.json + render.php)
│       └── card/                    # Repeatable card block (block.json + render.php)
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

| Directory                    | Target File Types          | Lint Standard | Dev Actions Allowed                                                                           | Prohibited / Restricted Actions                                                                                                                                        |
| ---------------------------- | -------------------------- | ------------- | --------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **Root (Theme Root)**        | `.php`, `.css`, `.json`    | PHPCS (WPCS)  | Edit page templates (e.g., `page.php`, `single.php`, `archive.php`).                          | **Do NOT** write helper functions or business logic here. Keep code thin. Bootstrap in `functions.php` only.                                                           |
| **`includes/`**              | `.php`                     | PHPCS (WPCS)  | Add/modify theme setup hooks, custom action triggers, AJAX logic, CPTs, and helper functions. | **Do NOT** output raw HTML or inline CSS/scripts. Keep logic purely functional.                                                                                        |
| **`includes/acf-json/`**     | `.json`                    | None          | Automatically write and load field groups via ACF plugin interface.                           | **Do NOT** manually modify files in this directory. Always sync changes through the WP Admin ACF panel.                                                                |
| **`template-parts/blocks/`** | `block.json`, `render.php` | PHPCS (WPCS)  | Add/modify block metadata (`block.json`) and PHP rendering layouts (`render.php`).            | Use Tailwind CSS utility classes directly in `render.php`. Do **NOT** create separate `.css` files per block unless pseudo-elements/JS states require it.              |
| **`sources/js/`**            | `.js`                      | ESLint 10     | Write global eager scripts or feature-specific dynamic JS modules.                            | **Never** use `var` variables. Ensure dynamic module imports are properly checks-wrapped before loading.                                                               |
| **`sources/css/`**           | `.css`                     | Stylelint 17  | Define `@theme` design tokens, `@utility` helper classes, and custom menu CSS.                | **No SCSS files**. Do **NOT** hardcode hex color values. Always use `@theme` tokens or standard Tailwind utilities. **Do NOT use arbitrary bracket values (`[...]`)**. |
| **`assets/`**                | `.js`, `.css`, `.json`     | None          | Target path for production bundles and development HMR scripts.                               | **NEVER** edit files in this folder manually. Any custom changes will be deleted on the next `npm run build`.                                                          |

---

## 5. Toolchain & Command Reference

Manage dependencies and run linting/compiling processes using `package.json` scripts:

```bash
npm run dev          # Spin up local Vite HMR server
npm run build        # Build minified assets for production (Tailwind v4 native tree-shaking)

npm run lint         # Check JS + CSS code quality
npm run lint:fix     # Automatically fix JS + CSS formatting issues

npm run phpcs        # Validate PHP files against WordPress coding standards
npm run phpcbf       # Automatically format PHP scripts (WPCS)
```

---

## 6. Asset Pipeline & Enqueues (Vite 8 + Tailwind CSS v4)

Frontend compilation uses **Vite 8** with **Tailwind CSS v4** (`@tailwindcss/vite`) to build JavaScript modules, process utility CSS, and output cache-busting production files.

### Development Mode

During development, the theme loads files directly from the Vite dev server (default `http://127.0.0.1:5173`):

1. `theme-scripts.php` initializes a connection request with `viteDevServerUrl()`.
2. It enqueues the local Vite client (`/@vite/client`) to activate HMR.
3. Dev assets (`sources/css/style.css` and `sources/js/script.js`) are loaded dynamically.
4. Active filter hooks add `type="module"` to enqueued scripts.

### Production Mode

For production (`npm run build`), Vite creates a dependency manifest at `assets/.vite/manifest.json`:

1. It reads the entry points: `sources/js/script.js`, `sources/css/style.css`, and dynamically located scripts under `sources/js/modules/**/*.js`.
2. Tailwind v4 scans all PHP and JS files declared in `@source` directives inside `sources/css/style.css` and tree-shakes unused utilities automatically.
3. Assets are built, chunk-split, optimized, and saved under `assets/` with cache-busting hashes.
4. `theme-scripts.php` parses `manifest.json` using the manifest key `sources/css/style.css` and loads the optimized file paths.

---

## 7. ACF Gutenberg Blocks Architecture

Custom ACF blocks use the modern **ACF Blocks V2 (`block.json`)** architecture.

```text
template-parts/blocks/{block-name}/
├── block.json     → Metadata (name, title, category, icon, supports, styles, example)
└── render.php     → Template (ABSPATH guard, field assignment, early return, get_block_wrapper_attributes)
```

### New Block Checklist

1. **Metadata**: Create `template-parts/blocks/{block-name}/block.json`.
2. **Template**: Create `template-parts/blocks/{block-name}/render.php` using Tailwind CSS utility classes.
3. **Register**: Add `/template-parts/blocks/{block-name}` to the `$blocks` array in `includes/acf-block-register.php`.
4. **ACF JSON**: Create/export field group to `includes/acf-json/group_{block-name}.json`.

---

## 8. JavaScript System & Core Modules

The Javascript architecture optimizes loading speeds by splitting execution:

- **Eager scripts**: Critical components loaded immediately (e.g. `deviceMenu.js`).
- **Lazy scripts**: Async assets imported dynamically only if their matching HTML selectors exist on the page (e.g. `swiperInit.js`, `fancybox.js`).

---

## 9. Tailwind CSS v4 Guidelines & Architecture

### CSS Architecture

```text
sources/css/
├── style.css   → Primary entry (@import "tailwindcss", @source, @theme, @utility)
└── menu.css    → Navigation styles (desktop nav, hamburger, mobile panel)
```

### Key Rules

1. **Design Tokens (`@theme`)**: Declare all primary/secondary colors, fonts, shadows, and heights under `@theme` in `sources/css/style.css`.
2. **No Hardcoded Hex Values**: Always reference color tokens via `var(--color-primary)` or Tailwind classes (`text-primary`, `bg-secondary`).
3. **No Arbitrary Bracket Values**: Do **not** use arbitrary bracket syntax in utility classes (e.g., `w-[320px]`, `text-[15px]`, `aspect-[16/9]`). Always use standard Tailwind utilities or add tokens to `@theme`.
4. **Reusable Utilities (`@utility`)**: Use `@utility` for complex or repeated class combinations (`btn`, `container-wrap`, `h-banner`, `max-w-container`, `shadow-header`, `wc-desc`, `bullet-styled`).

---

## 10. PHP Hook & Utility Architecture

Business logic is organized into module files in `includes/`:

- **`theme-functions.php`**: Utility functions (`placeholder_image()`, `acf_svg()`, `entry_banner()`, `acf_link()`, `acf_image()`).
- **`theme-action.php`**: Custom action hooks, emoji removal, and AJAX search handlers (`wpnest_ajaxsearch_filter`).
- **`theme-filter.php`**: Filters, head tag cleanups, and SVG mime upload support.
- **`theme-security.php`**: Security hardening rules (XML-RPC disabler, REST API user endpoint protection, author archive redirects).
- **`theme-cpt.php`**: Custom Post Types and Taxonomies registration blueprint.

---

## 11. Security Hardening & Privacy

- **Direct Execution Guard**: Every PHP file begins with `if ( ! defined( 'ABSPATH' ) ) { exit; }`.
- **Output Escaping**: Mandatory `esc_html()`, `esc_attr()`, `esc_url()`, and `wp_kses_post()`.
- **AJAX Nonces**: Mandatory `wp_verify_nonce()` verification.
- **REST API User Protection**: `/wp/v2/users` endpoint restricted for unauthenticated requests.
- **XML-RPC Protection**: XML-RPC interface disabled via `xmlrpc_enabled` filter.

---

## 12. CI/CD Automated Pipelines

Automated integration runs via three GitHub Actions workflows in `.github/workflows/`:

1. **Code Standards Checks (`code-standards.yml`)**: Runs ESLint, Stylelint, PHPCS, and Vite build on push.
2. **PR Checks (`pr-check.yml`)**: Validates PR titles and change sizes.
3. **Release Auto-Pack (`release.yml`)**: Packages production theme ZIPs on tag push.
