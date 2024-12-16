import defaultTheme from 'tailwindcss/defaultTheme';
import preset from "./vendor/wireui/wireui/tailwind.config.js"
import forms from '@tailwindcss/forms'

/** @type {import('tailwindcss').Config} */
export default {
    presets: [
        preset,
    ],
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',

        "./vendor/wireui/wireui/src/*.php",
        "./vendor/wireui/wireui/ts/**/*.ts",
        "./vendor/wireui/wireui/src/WireUi/**/*.php",
        "./vendor/wireui/wireui/src/Components/**/*.php",
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Helvetica Neue', ...defaultTheme.fontFamily.sans],
            },
        },
    },
    plugins: [forms],
};
