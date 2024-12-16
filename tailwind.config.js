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
                primary: colors.amber,
                secondary: colors.neutral,
                positive: colors.teal,
                negative: colors.rose,
                warning: colors.yellow,
                info: colors.sky,

                'background-dark': colors.neutral["900"],
            },
        },
    },
    plugins: [forms],
};
