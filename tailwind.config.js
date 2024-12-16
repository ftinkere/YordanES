import defaultTheme from 'tailwindcss/defaultTheme';
import preset from "./vendor/wireui/wireui/tailwind.config.js"
import forms from '@tailwindcss/forms'
import colors from "tailwindcss/colors";

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

    darkMode: ['variant', [
        '@media (prefers-color-scheme: dark) { &:not(.light *) }',
        '&:is(.dark *)',
    ]],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Helvetica Neue', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: colors.orange,
                secondary: colors.neutral,
                positive: colors.emerald,
                negative: colors.red,
                warning: colors.amber,
                info: colors.sky,
                gray: colors.neutral,

                'background-dark': colors.neutral["900"],
            },
        },
    },
    plugins: [forms],
};
