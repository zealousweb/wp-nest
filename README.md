# WPNest Theme

A starter theme for WordPress with modern JavaScript and CSS tooling. This theme leverages Webpack, Sass, and modern JavaScript features to build a highly optimized, scalable, and automated WordPress theme.

---

## 🚀 Features

- **Webpack 5** for modern module bundling and asset optimization.
- **Babel** for ES6+ JavaScript transpilation.
- **Dart Sass** via sass-loader for compiling SCSS styles using the latest Sass syntax.
- **Modular Architecture** using `@use` and `@forward` for scalable and maintainable styles.
- **Asset Optimization**: `MiniCssExtractPlugin` (CSS extraction), `TerserPlugin` (JS minifier), and `CssMinimizerPlugin` (CSS minifier).
- **Tree-shaking** & **PurgeCSS** to automatically remove unused code in production.
- **Webpack Manifest Plugin** to generate an asset manifest for smart enqueuing.
- **Husky & lint-staged** for automated pre-commit code verification and commit message checks (`cspell`, length).
- **GitHub Actions CI/CD** for continuous integration and automated theme releases.

---

## 🛠️ Code Standards & Auto-Formatting

This theme strictly enforces high-quality code standards:

- **JavaScript**: ESLint v9 (Custom config with strict whitespace rules)
- **SCSS**: Stylelint v17
- **PHP**: PHP_CodeSniffer (configured to WordPress standards via `.phpcs.xml`)
- **VS Code Native Integration**: `settings.json` and `tasks.json` provide built-in auto-format on save.

**How Auto-Format Works:**
When running `npm run watch`, Webpack's `NativeLintPlugin` intercepts every file save and automatically runs `--fix` for ESLint and Stylelint. On commit, `lint-staged` automatically formats your `*.php`, `*.js`, and `*.scss` files before allowing the commit.

---

## 📦 Installation & Setup

### Prerequisites

- [Node.js](https://nodejs.org/) (version 20 or later)
- [Composer](https://getcomposer.org/) (for managing PHP dependencies)

### Step 1: Clone the Repository

```bash
git clone https://github.com/zealousweb/wp-nest.git
cd wp-nest
```

### Step 2: Install Dependencies

```bash
npm install
composer install
```

---

## ⌨️ Command Reference

### 🔄 Development (Watch & Auto-Fix)

The watch command automatically compiles assets **and auto-fixes JS/SCSS errors on save**.

```bash
npm run watch
```

### 🧹 Linting (Check Only)

Check if your files match the coding standards.

```bash
npm run lint         # Checks both JS and SCSS
npm run lint:js      # Checks JavaScript files
npm run lint:scss    # Checks SCSS files
npm run phpcs        # Checks PHP files against WordPress standards
```

### 🔧 Auto-Fixing

Manually force the linters to fix all fixable errors in your codebase.

```bash
npm run lint:fix       # Auto-fixes JS and SCSS
npm run lint:js:fix    # Auto-fixes JavaScript only
npm run lint:scss:fix  # Auto-fixes SCSS only
npm run phpcbf         # Auto-fixes PHP files
```

### 🏗️ Production Build

Build the theme for production. This will minify JS/CSS, generate the asset manifest, and run PurgeCSS.

```bash
npm run build
```

---

## 🤖 CI/CD Pipeline (GitHub Actions)

We have three automated workflows running on GitHub Actions:

1. **Code Standards (CI)**
   _Runs on push to feature/stage/master branches._
   Parallel execution of ESLint, Stylelint, and PHPCS. If they pass, it runs the Webpack build to ensure nothing is broken.

2. **Release Build (CD)**
   _Runs when a version tag (e.g., `v1.0.3`) is pushed._
   Runs all linters, creates a production build, packages the theme (excluding dev files), and automatically publishes a GitHub Release with the ZIP file.

3. **Pull Request Checks**
   _Runs on all PRs targetting master/stage._
   Validates PR title length and warns if the PR modifies too many files.

---

## 📚 Documentation

For rules on commit messages, creating pull requests, and the branching strategy, please read the **[CONTRIBUTING.md](CONTRIBUTING.md)** file.

For the version history of this theme, see the **[CHANGELOG.md](CHANGELOG.md)** file.
