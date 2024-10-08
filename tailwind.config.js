import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                dark: {
                    bg: '#1a1a1a',
                    text: '#e0e0e0',
                },
                light: {
                    bg: '#ffffff',
                    text: '#333333',
                },
            },
        },
    },

    plugins: [forms],

    darkMode: 'class',
};
