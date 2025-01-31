import defaultTheme from 'tailwindcss/defaultTheme';
import preset from "./vendor/wireui/wireui/tailwind.config.js"
import forms from '@tailwindcss/forms'
import typography from '@tailwindcss/typography'
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

        "./vendor/livewire/flux-pro/stubs/**/*.blade.php",
        "./vendor/livewire/flux/stubs/**/*.blade.php",
    ],

    // darkMode: ['variant', [
    //     '@media (prefers-color-scheme: dark) { &:not(.light *) }',
    //     '&:is(.dark *)',
    // ]],

    darkMode: 'selector',

    theme: {
        extend: {
            fontFamily: {
                sans: ['InterVariable', 'Inter', 'Helvetica Neue', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: colors.amber,
                secondary: colors.zinc,
                positive: colors.teal,
                negative: colors.rose,
                warning: colors.yellow,
                info: colors.sky,

                'background-dark': colors.zinc["900"],

                // zinc: colors.neutral,

                accent: {
                    DEFAULT: 'var(--color-accent)',
                    content: 'var(--color-accent-content)',
                    foreground: 'var(--color-accent-foreground)',
                },
            },
        },
    },
    plugins: [forms, typography],
};
