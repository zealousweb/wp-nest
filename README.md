# WPNest Theme

A WordPress starter theme with a modern **Vite 8** asset pipeline, strict code standards, and a clean ACF block architecture. Designed as a reusable project skeleton — configure once, build fast.

---

## 🚀 Features

- **Vite 8** for lightning-fast HMR, module bundling, and production builds.
- **Dart Sass** for SCSS compilation with `@use` / `@forward` modular architecture.
- **PurgeCSS** + **Autoprefixer** + **cssnano** for production-optimised CSS.
- **ESLint 10** (flat config) for JavaScript standards enforcement.
- **Stylelint 17** for SCSS standards enforcement.
- **PHP_CodeSniffer** (WPCS) for WordPress PHP standards.
- **Husky + lint-staged** for automated pre-commit code formatting and verification.
- **GitHub Actions CI/CD** for continuous integration and automated theme releases.
- **ACF Block system** with a single render callback, preview image support, and synced JSON.
- **Lazy-loaded JS modules** via native dynamic `import()` for performance.
- **Manifest-based asset enqueuing** — no hardcoded filenames in PHP.

---

## 🛠️ Code Standards & Auto-Formatting

| Language | Tool | Config |
|---|---|---|
| JavaScript | ESLint 10 | `config/config.eslint.js` |
| SCSS | Stylelint 17 | `config/config.stylelint.js` |
| PHP | PHPCS (WPCS) | `.phpcs.xml` |

On every `git commit`, `lint-staged` automatically runs ESLint, Stylelint, and PHPCBF on staged files before the commit goes through.

For the best editor experience, use **VS Code** — workspace settings in `.vscode/` configure format-on-save and recommended extensions automatically.

---

## 📦 Installation & Setup

### Prerequisites

- [Node.js](https://nodejs.org/) v23.11.1 (see `engines` in `package.json`)
- [Composer](https://getcomposer.org/) for PHP dependencies
- [Local](https://localwp.com/) or any local WordPress environment

### Step 1: Clone the Repository

```bash
git clone https://github.com/zealousweb/wp-nest.git wp-your-project
cd wp-your-project
```

### Step 2: Configure for Your Project

Before installing, rename the theme for your project in **4 places**:

| File | What to change |
|---|---|
| `functions.php` lines 21–22 | Change `'wpnest'` in `THEME_PREFIX` and `TEXT_DOMAIN` |
| `style.css` line 12 | Change `Text Domain: wpnest` |
| `.env` line 4 | Change `VITE_DEV_THEME_NAME = wp-nest` |
| All PHP files | Find & replace the `wpnest_` function prefix (e.g. → `myproject_`) |

> **Note:** `THEME_PREFIX` is used for script handles automatically. PHP function names like `wpnest_setup()` must be renamed manually since PHP constants cannot be used in function definitions.

### Step 3: Configure the Dev Environment

Copy `.env` and set your local server URL:

```bash
# .env
VITE_DEV_SERVER = http://127.0.0.1     # your local WordPress URL
VITE_DEV_SERVER_PORT = 5173
VITE_DEV_THEME_NAME = your-theme-name  # must match the theme folder name
```

### Step 4: Install Dependencies

```bash
npm install
composer install
```

---

## ⌨️ Command Reference

### 🔄 Development

Start the Vite dev server with HMR and live reload:

```bash
npm run dev
```

### 🏗️ Production Build

Build and optimise all assets for production (minification, PurgeCSS, autoprefixer):

```bash
npm run build
```

### 🧹 Linting (Check Only)

```bash
npm run lint          # Check both JS and SCSS
npm run lint:js       # Check JavaScript only
npm run lint:scss     # Check SCSS only
npm run phpcs         # Check PHP against WordPress Coding Standards
```

### 🔧 Auto-Fixing

```bash
npm run lint:fix      # Auto-fix JS and SCSS
npm run lint:js:fix   # Auto-fix JavaScript only
npm run lint:scss:fix # Auto-fix SCSS only
npm run phpcbf        # Auto-fix PHP files
```

---

## 📁 Theme Architecture

```
functions.php                 → Bootstrap (requires only, no logic)
includes/
├── theme-scripts.php         → Asset enqueuing (Vite manifest)
├── theme-functions.php       → Helper functions
├── theme-action.php          → Actions & AJAX handlers
├── theme-filter.php          → Filters
├── theme-security.php        → Security hardening
├── acf-block-register.php    → ACF block registration & render callback
└── acf-json/                 → ACF field group JSON (auto-synced)

template-parts/blocks/        → ACF block templates ({block-slug}.php)
includes/acf-block-preview/   → Block preview images ({block-slug}.jpg)

sources/
├── js/
│   ├── script.js             → Main entry (eager: menu; lazy: modules)
│   ├── modules/              → Lazy-loaded ES modules
│   └── common/               → Named export modules
└── scss/
    ├── style.scss            → Main entry
    ├── abstracts/            → Variables, mixins, functions
    ├── base/                 → Reset, typography, global
    └── components/           → One partial per block (_block-name.scss)

assets/                       → Compiled output — never edit directly
config/                       → Linting and build configs
```

### Key Constants (`functions.php`)

```php
define( 'THEME_PREFIX', 'your-project' ); // Used for script/style handles
define( 'TEXT_DOMAIN', 'your-project' );  // Used in all __() translation calls
```

Change **only these two lines** to migrate the text domain for a new project.

---

## 🧱 Adding a New ACF Block

Follow these 4 steps:

1. **Register** — add the block array to `includes/acf-block-register.php`
2. **Template** — create `template-parts/blocks/{block-slug}.php`
3. **Styles** — create `sources/scss/components/_{block-slug}.scss` and import in `_core.scss`
4. **ACF JSON** — export field group to `includes/acf-json/group_{block-slug}.json`

Optionally add `includes/acf-block-preview/{block-slug}.jpg` for the block preview image in the editor.

---

## 🤖 CI/CD Pipeline (GitHub Actions)

Three automated workflows run on push:

| Workflow | Trigger | What it does |
|---|---|---|
| `code-standards.yml` | Push to `feature/*`, `stage`, `master` | Runs ESLint, Stylelint, PHPCS in parallel, then Vite build |
| `release.yml` | Tag push (e.g. `v1.0.3`) | Lints, builds, packages theme ZIP, publishes GitHub Release |
| `pr-check.yml` | All PRs to `master` / `stage` | Validates PR title length, warns if >50 files changed |

---

## 📚 Documentation

- **Contributing guidelines, branching strategy & commit rules** → [CONTRIBUTING.md](CONTRIBUTING.md)
- **Version history** → [CHANGELOG.md](CHANGELOG.md)
