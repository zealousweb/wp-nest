---
trigger: manual
---

# Toolchain — SITE_NAME

## Commands

- JS Lint → `npm run lint:js`
- JS Lint Fix → `npm run lint:js:fix`
- JS Test → `npm run test:js`
- SCSS Lint → `npm run lint:scss`
- SCSS Lint Fix → `npm run lint:scss:fix`
- PHP Lint → `npm run phpcs`
- Dev → `npm run dev`
- Build → `npm run build`

## Run After Changes

- **PHP** → `npm run phpcs`
- **JavaScript** → `npm run lint:js` + `npm run test:js` + `npm run build`
- **SCSS** → `npm run lint:scss` + `npm run build`
- **New Block** → Run all relevant checks + build

## Rules

- Fix all lint/test errors before completion.
- Don't disable lint rules without justification.
- Ensure `npm run build` completes successfully.
