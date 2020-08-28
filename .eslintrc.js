module.exports = {
  env: {
    browser: true,
    es2020: true
  },
  extends: [
    'plugin:vue/essential',
    'airbnb-base'
  ],
  parserOptions: {
    ecmaVersion: 12,
    sourceType: 'module'
  },
  plugins: [
    'vue'
  ],
  rules: {
    'linebreak-style': [
      'error',
      'windows'
    ],
    'no-tabs': 'off',
    'eol-last': 'off',
    'comma-dangle': [
      'error',
      'never'
    ],
    'no-underscore-dangle': 'off',
    'no-param-reassign': 'off',
    'space-before-function-paren': 'off',
    'operator-linebreak': 'off',
    'max-len': 'off',
    'no-plusplus': 'off',
    'nonblock-statement-body-position': 'off',
    'function-paren-newline': 'off',
    'no-console': 'off',
    'import/no-extraneous-dependencies': ['error', { devDependencies: true }]
  }
};
