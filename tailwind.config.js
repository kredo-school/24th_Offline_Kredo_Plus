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
            colors: {
                brand: {
                    blue: '#2f5fdb',
                    indigo: '#4338ca',
                    steel: '#3f6db3',
                    dark: '#1e3a8a',
                    red: '#e05237',
                    green: '#5eab35',
                    yellow: '#f5b52e',
                },
            },
            fontFamily: {
                sans: ['"Noto Sans JP"', ...defaultTheme.fontFamily.sans],
                display: ['"Poppins"', '"Noto Sans JP"', ...defaultTheme.fontFamily.sans],
            },
            boxShadow: {
                card: '0 12px 32px -12px rgba(30, 58, 138, 0.22)',
                'card-hover': '0 20px 44px -12px rgba(30, 58, 138, 0.30)',
                soft: '0 2px 12px rgba(30, 58, 138, 0.08)',
            },
        },
    },

    plugins: [forms],
};
