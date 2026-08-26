import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Plus Jakarta Sans', ...defaultTheme.fontFamily.sans],
                display: ['Fraunces', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                organic: {
                    green: '#1F4D3A',
                    'green-light': '#2D6A4F',
                    gold: '#F2A93B',
                    tomato: '#E85C4A',
                    cream: '#FAFAF7',
                    charcoal: '#1C1C1A',
                },
            },
        },
    },

    plugins: [forms],
};