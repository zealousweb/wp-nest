# Changelog

All notable changes to the WP-Nest theme will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- GitHub Actions CI/CD workflows (`code-standards.yml`, `release.yml`, `pr-check.yml`).
- EditorConfig, ESLint, Stylelint, and PHPCS configurations.
- VS Code workspace settings (`settings.json`, `tasks.json`, `extensions.json`) for seamless team onboarding.
- Dynamic `NativeLintPlugin` in webpack to auto-fix JS and SCSS on save (via `npm run watch`).

### Changed

- `config.stylelint` converted to CommonJS (`.cjs`) for full VS Code extension support.
- PHP coding standards enforced to WordPress standards via `.phpcs.xml`.
- Refactored core theme functions (`includes/theme-*.php`) to meet WordPress security standards (`wp_kses_post`, `wp_safe_redirect`).

### Fixed

- Prevented unsafe `git push` execution from within the `commit-msg` Husky hook.
- Enforced maximum of 1 empty line, trailing whitespace stripping, and mandatory EOF newline via ESLint.

## [1.0.2] - 2026-03-01

### Added

- Initial theme skeleton setup.
- Webpack 5 configuration for asset bundling and optimization.
- SCSS structure following the 7-1 pattern.
- ACF block registration for `custom_block` and `wysiwyg`.
- Core JavaScript modules (Swiper, Fancybox).

### Security

- Disabled author archive pages.
- Removed `/wp/v2/users` REST API endpoint access for unauthenticated users.
