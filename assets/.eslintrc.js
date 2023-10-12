module.exports = {
  "parser": "@typescript-eslint/parser",
  "extends": [
    "eslint:recommended",
    "plugin:@typescript-eslint/eslint-recommended",
    "plugin:@typescript-eslint/recommended",
    "plugin:import/warnings",
    "prettier"
  ],
  "env": {
    "browser": true,
    "es6": true
  },
  "plugins": [
    "@typescript-eslint",
    "import",
    "header"
  ],
  "rules": {
    "@typescript-eslint/no-explicit-any": "off",
    "header/header": [2, "block", [
      "!",
      " * (c) Christian Gripp <mail@core23.de>",
      " *",
      " * For the full copyright and license information, please view the LICENSE",
      " * file that was distributed with this source code.",
      " "
    ], 2]
  },
  "overrides": [
    {
      "files": [
        "test/*.js"
      ],
      "extends": [
        "plugin:jest/recommended"
      ]
    }
  ]
};
