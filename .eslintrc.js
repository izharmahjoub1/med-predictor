module.exports = {
  env: {
    browser: true,
    es2021: true,
    node: true,
  },
  extends: [
    'eslint:recommended',
    'plugin:@typescript-eslint/recommended',
  ],
  parser: '@typescript-eslint/parser',
  parserOptions: {
    ecmaVersion: 'latest',
    sourceType: 'module',
  },
  plugins: ['@typescript-eslint'],
  rules: {
    'indent': ['error', 2],
    'linebreak-style': ['error', 'unix'],
    'quotes': ['error', 'single'],
    'semi': ['error', 'always'],
    'no-unused-vars': 'warn',
    'no-console': 'warn',
    'prefer-const': 'error',
    'no-var': 'error',
    'object-shorthand': 'error',
    'prefer-template': 'error',
    'template-curly-spacing': 'error',
    'arrow-spacing': 'error',
    'no-duplicate-imports': 'error',
    'no-useless-rename': 'error',
    'prefer-destructuring': 'error',
    'prefer-spread': 'error',
    'prefer-rest-params': 'error',
    'no-useless-constructor': 'error',
    'no-useless-return': 'error',
    'no-useless-catch': 'error',
    'no-useless-escape': 'error',
    'no-useless-concat': 'error',
    'no-useless-computed-key': 'error',
    'no-useless-rename': 'error',
    'no-useless-return': 'error',
    'no-useless-catch': 'error',
    'no-useless-escape': 'error',
    'no-useless-concat': 'error',
    'no-useless-computed-key': 'error',
  },
  overrides: [
    {
      files: ['*.js'],
      parserOptions: {
        sourceType: 'script',
      },
    },
    {
      files: ['*.blade.php'],
      env: {
        browser: true,
        es6: true,
      },
      globals: {
        // Laravel Blade globals
        $: 'readonly',
        jQuery: 'readonly',
        // Custom globals
        SpeechRecognitionService: 'readonly',
        ModeManager: 'readonly',
      },
    },
  ],
  globals: {
    // Browser globals
    window: 'readonly',
    document: 'readonly',
    console: 'readonly',
    // Laravel globals
    route: 'readonly',
    asset: 'readonly',
    csrf_token: 'readonly',
    // Custom globals
    SpeechRecognitionService: 'readonly',
    ModeManager: 'readonly',
  },
};
