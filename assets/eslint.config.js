const {
    defineConfig,
} = require("eslint/config");

const tsParser = require("@typescript-eslint/parser");

const {
    fixupConfigRules,
    fixupPluginRules,
} = require("@eslint/compat");

const globals = require("globals");
const typescriptEslint = require("@typescript-eslint/eslint-plugin");
const _import = require("eslint-plugin-import");
const header = require("eslint-plugin-header");
header.rules.header.meta.schema = false;

const js = require("@eslint/js");

const {
    FlatCompat,
} = require("@eslint/eslintrc");

const compat = new FlatCompat({
    baseDirectory: __dirname,
    recommendedConfig: js.configs.recommended,
    allConfig: js.configs.all
});

module.exports = defineConfig([{
    languageOptions: {
        parser: tsParser,

        globals: {
            ...globals.browser,
        },
    },

    extends: fixupConfigRules(compat.extends(
        "eslint:recommended",
        "plugin:@typescript-eslint/eslint-recommended",
        "plugin:@typescript-eslint/recommended",
        "plugin:import/warnings",
        "prettier",
    )),

    plugins: {
        "@typescript-eslint": fixupPluginRules(typescriptEslint),
        import: fixupPluginRules(_import),
        header,
    },

    "rules": {
        "@typescript-eslint/no-explicit-any": "off",

        "header/header": [2, "block", [
            "!",
            " * (c) Christian Gripp <mail@core23.de>",
            " *",
            " * For the full copyright and license information, please view the LICENSE",
            " * file that was distributed with this source code.",
            " ",
        ], 2],
    },
}, {
    files: ["test/*.js"],
    extends: compat.extends("plugin:jest/recommended"),
}]);
