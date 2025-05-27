# WPNest Theme

A starter theme for WordPress with modern JavaScript and CSS tooling. This theme leverages Webpack, Sass, and modern JavaScript features to build a highly optimized, scalable WordPress theme.

## 🚀 Features

- **Webpack 5** for modern module bundling and asset optimization.
- **Babel** for ES6+ JavaScript transpilation.
- **Dart Sass** via sass-loader for compiling SCSS styles using the latest Sass syntax and performance improvements.
- **Modular SCSS Architecture** using @use and @forward for scalable and maintainable styles.
- **MiniCssExtractPlugin** for extracting and minimizing CSS into separate files.
- **TerserPlugin** for JavaScript minification.
- **CssMinimizerPlugin** for CSS optimization.
- **Tree-shaking** to automatically remove unused JavaScript in production.
- **Modular JavaScript Structure:** Organize scripts by component or feature for better separation and reusability.
- **Scoped Component Styling:** Encapsulate styles per module to prevent global conflicts.
- **Webpack Manifest Plugin** to generate an asset manifest for server-side integration.
- **ESLint and Stylelint** for enforcing code standards in JavaScript and SCSS.
- **Husky** and **lint-staged** to run linters automatically before commits, ensuring clean code.
- **PHP Code Sniffer (phpcs)** for enforcing WordPress and PSR-12 PHP coding standards.
- **PHP Code Beautifier and Fixer (phpcbf)** for auto-fixing PHP coding standard issues.

## Installation

To get started with this theme, follow these steps:

### Prerequisites

- [Node.js](https://nodejs.org/) (version 20 or later)
- [Composer](https://getcomposer.org/) (for managing PHP dependencies)

### Step 1: Clone the Repository

git clone https://github.com/zealousweb/wp-nest.git
cd wp-nest


### 🛠 All Commands Included:

1. **Installation:**
   - `npm install` : Install Node.js dependencies.
   - `composer install` : Install PHP dependencies for WordPress.

2. **Development Commands:**
   - `npm run watch` : Watch for file changes during development.

3. **Linting Commands:**
   - `npm run lint:js` : Lint JavaScript files.
   - `npm run lint:js:fix` : Automatically fix JavaScript linting issues.
   - `npm run lint:scss` : Lint SCSS files.
   - `npm run lint:scss:fix` : Automatically fix SCSS linting issues.
   - `npm phpcs` :  PHP files using PSR12 standard.
   - `npm phpcbf` : php code beautifier and fixer.
   - `npm run lint` : Run all linting checks.
   - `npm run lint:fix` : Automatically fix all linting issues.

4. **Building Commands:**
   - `npm run build`  : Build the theme for production (optimized and minified).
   - **PurgeCSS**: Automatically removes unused CSS by scanning .php and .js files.
   - **Safelist**: Customize the safelist in purgecss-safelist.js to prevent removal of dynamic or JS-generated class names.
