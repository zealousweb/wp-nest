# Contributing to WP-Nest

First off, thank you for considering contributing to the WP-Nest theme!

The following is a set of guidelines for contributing to this project. These are mostly guidelines, not rules. Use your best judgment, and feel free to propose changes to this document in a pull request.

## Table of Contents

1. [Code Standards](#code-standards)
2. [Development Setup](#development-setup)
3. [Branching Strategy](#branching-strategy)
4. [Commit Messages](#commit-messages)
5. [Pull Request Process](#pull-request-process)

---

## Code Standards

We take code quality seriously. This project enforces strict coding standards using automated tools.

- **JavaScript**: ESLint (Custom rules in `config/config.eslint.js`)
- **CSS/SCSS**: Stylelint (Custom rules in `config/config.stylelint.cjs`)
- **PHP**: PHP_CodeSniffer (WordPress standard via `.phpcs.xml`)

### Editor Setup

For the best experience, use **VS Code** (or Antigravity). We have included workspace settings in `.vscode/` that will automatically prompt you to install required extensions and configure format-on-save for you.

Simply open the project folder in your editor and accept the recommended extensions.

---

## Development Setup

1. **Install Node.js**: Ensure you are using Node.js v20 or higher.
2. **Install Composer**: Ensure PHP and Composer are installed.
3. **Install Dependencies**:
    ```bash
    npm install
    composer install
    ```

### Daily Workflow

To compile assets and start the local development watcher (which also **auto-fixes** JS and SCSS on save):

```bash
npm run watch
```

To manually verify code standards:

```bash
npm run lint      # Check JS & SCSS
npm run phpcs     # Check PHP
```

To automatically fix code formatting issues:

```bash
npm run lint:fix  # Auto-fix JS & SCSS
npm run phpcbf    # Auto-fix PHP
```

---

## Branching Strategy

We follow a structured branching model:

- `master` / `main`: Production-ready code only.
- `stage`: Pre-production testing environment.
- `feature/*`: New features (e.g., `feature/hero-slider`).
- `enhancement/*`: Improvements to existing features (e.g., `enhancement/header-a11y`).
- `bugfix/*`: Fixes for minor, non-critical bugs.
- `hotfix/*`: Emergency fixes directly pushed to production.

**Never push directly to `master` or `stage`.** Always create a branch and open a Pull Request.

---

## Commit Messages

We use **Husky** validation to ensure clean commit history.

- Commit messages must be **spelled correctly** (enforced by `cspell`).
- Commit messages must be between **10 and 99 characters** long.

**Good Commit Examples:**

- `Fix mobile menu overflowing on small screens`
- `Add ACF block for testimonials`
- `Update webpack config to minify CSS`

**Bad Commit Examples:**

- `fix` (Too short)
- `fixed the thing` (Vague)
- `sdgfsdfs df` (Spelling error / gibberish)

_Note: Before any commit goes through, `lint-staged` will automatically run ESLint, Stylelint, and PHPCBF on your staged files to fix any formatting errors._

---

## Pull Request Process

1. **Update the CHANGELOG.md**: If your PR introduces a new feature, changes existing behavior, or fixes a bug, document it under the `[Unreleased]` section.
2. **Pass CI Checks**: Once you open a PR, GitHub Actions will automatically run.
    - Your code _must_ pass ESLint, Stylelint, PHPCS, and Webpack build steps.
    - The PR title must be between 10 and 99 characters.
    - If your PR modifies more than 50 files, it will trigger a manual review warning.
3. **Review**: Wait for at least one code review approval before merging.
4. **Squash and Merge**: Keep the history clean by squashing your branch into a single commit upon merge.
