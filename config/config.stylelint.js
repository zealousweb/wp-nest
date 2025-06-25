// config.stylelint.js
export default {
    extends: ['stylelint-config-standard-scss', 'stylelint-config-clean-order/error'],
    plugins: ['stylelint-scss'],
    rules: {
      'scss/at-rule-no-unknown': true,
      'scss/at-else-empty-line-before': 'never',
      'scss/at-if-closing-brace-newline-after': null,
      'scss/at-if-closing-brace-space-after': null,
      'scss/at-else-closing-brace-newline-after': null,
      'scss/at-else-closing-brace-space-after': null,
      'selector-class-pattern': '^[a-z0-9\\-]+$', // kebab-case only
      'at-rule-no-unknown': null,
      'max-nesting-depth': [4, { ignore: ['pseudo-classes'] }],
      'color-named': 'never',
      'declaration-block-no-duplicate-properties': true,
      'property-no-unknown': true,
      'value-keyword-case': 'lower',
      'unit-no-unknown': true,
      'length-zero-no-unit': true,
      'shorthand-property-no-redundant-values': true,
      'at-rule-empty-line-before': ['always', {
        except: ['blockless-after-same-name-blockless', 'first-nested'],
        ignore: ['after-comment', 'inside-block']
      }],
      'property-no-vendor-prefix': null,
      'value-no-vendor-prefix': null,
      'font-family-name-quotes': null,
    },      
    overrides: [
      {
        files: ['**/*.scss'],
        customSyntax: 'postcss-scss',
      },
    ],
};