// http://eslint.org/docs/user-guide/configuring

module.exports = {
  root: true,
  env: {
    browser: true,
    node: true,
    es6: true,
    jquery: true,
  },
  globals: {
    google: true,
    document: true,
    navigator: false,
    window: true,
    JQuery: true,
    JQueryStatic: true,
  },
  parserOptions: {
    sourceType: "module",
    parser: '@babel/eslint-parser',
    requireConfigFile: false,
  },
  rules: {
    'class-methods-use-this': 0,
    'func-names': 0,
    'max-len': ['error', {code: 120}],
    'no-alert': 0,
    'no-bitwise': 0,
    'no-new': 0,
    'no-param-reassign': ['error', {props: false}],
    'no-restricted-globals': [
      'error',
      {
        name: 'global',
        message: 'Use window variable instead.',
      },
    ],
  },
  settings: {
    'import/resolver': 'webpack',
  },
};
