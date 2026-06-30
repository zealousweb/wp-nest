---
trigger: manual
---

---
description: Quality toolchain — commands reference
alwaysApply: false
---

# Toolchain — SITE_NAME

## Commands (from `package.json` and `composer.json`)

| Task          | Command                 |
| ------------- | ----------------------- |
| JS lint       | `npm run lint:js`       |
| JS lint fix   | `npm run lint:js:fix`   |
| JS test       | `npm run test:js`       |
| SCSS lint     | `npm run lint:scss`     |
| SCSS lint fix | `npm run lint:scss:fix` |
| Lint all      | `npm run lint`          |
| Lint fix all  | `npm run lint:fix`      |
| PHP lint      | `npm run phpcs`         |
| Dev build     | `npm run dev`           |
| Prod build    | `npm run build`         |

**Note:** There is no `npm run qa` suite or `composer run qa:php` — run the individual commands above.

## Configs

`config/config.eslint.js` · `config/config.stylelint.cjs` · `.phpcs.xml` · `phpunit.xml.dist` · `jest.config.js`

## When to run

- PHP change → `npm run phpcs` + `npm run test:php`
- JS change → `npm run lint:js` + `npm run test:js` + `npm run build`
- SCSS change → `npm run lint:scss` + `npm run build`
- New block → all above
- Pre-commit → Husky runs `lint-staged` automatically (ESLint, Stylelint, PHPCS on changed files)
- Delivery → all lint + test commands pass · `npm run build` produces clean `assets/`

## Agent rules

Fix failures before marking done · no rule disable without inline justification · Jest for interactive JS modules · PHPUnit for new PHP helper functions.
