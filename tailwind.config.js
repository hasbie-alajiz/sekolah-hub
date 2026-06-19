import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import daisyui from 'daisyui';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './app/Modules/**/*.blade.php',
        './themes/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'sidebar-bg': '#1E2A3B',
                'sidebar-active': '#253347',
                'sidebar-muted': '#94A3B8',
                'app-bg': '#F8F9FA',
                'border-light': '#E9ECEF',
                'heading-dark': '#111827',
                'body-dark': '#4B5563',
            },
        },
    },

    plugins: [forms, daisyui],
};
