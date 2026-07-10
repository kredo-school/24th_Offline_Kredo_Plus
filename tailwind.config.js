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
                // English hub (warm terracotta palette, sampled from the design mockup)
                primary: '#974018',
                'on-primary': '#ffffff',
                'primary-container': '#f4e8df',
                'on-surface': '#241814',
                'on-surface-variant': '#6b5d57',
                'surface-container-lowest': '#ffffff',
                'outline-variant': '#ede0d8',
            },
            fontFamily: {
                sans: ['"Noto Sans JP"', ...defaultTheme.fontFamily.sans],
                display: ['"Poppins"', '"Noto Sans JP"', ...defaultTheme.fontFamily.sans],
            },
            maxWidth: {
                'container-max': '1140px',
            },
            spacing: {
                'margin-mobile': '1.25rem',
                'margin-desktop': '2rem',
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
