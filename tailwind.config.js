const defaultTheme = require("tailwindcss/defaultTheme");

/** @type {import('tailwindcss').Config} */
module.exports = {
    darkMode: "class",
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                // Inter first; Hind Siliguri ensures Bengali script support
                sans: [
                    "Inter",
                    "Hind Siliguri",
                    ...defaultTheme.fontFamily.sans,
                ],
            },
        },
    },

    plugins: [require("@tailwindcss/forms")],
};
