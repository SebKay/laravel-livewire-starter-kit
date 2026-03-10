/** @type {import('prettier').Config & import('prettier-plugin-tailwindcss').PluginOptions} */
export default {
    plugins: ["prettier-plugin-blade", "prettier-plugin-tailwindcss"],
    tailwindStylesheet: "./resources/css/app.css",
    tabWidth: 4,
    overrides: [
        {
            files: "*.blade.php",
            options: {
                parser: "blade",
                bladePhpFormatting: "off",
            },
        },
    ],
};
