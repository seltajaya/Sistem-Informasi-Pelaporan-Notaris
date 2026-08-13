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
                sans: ['"Plus Jakarta Sans"', ...defaultTheme.fontFamily.sans],
                display: ['"Plus Jakarta Sans"', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                kumham: {
                    50: '#F1F5FE',
                    100: '#DDE7FB',
                    200: '#B9CBF7',
                    300: '#86A8EF',
                    400: '#4E7DDF',
                    500: '#2754B8',
                    600: '#1D3B8F',
                    700: '#162A6B',
                    800: '#122352',
                    900: '#0E1B45',
                    950: '#091128',
                },
                emas: {
                    50: '#FBF6E6',
                    100: '#F8EFD3',
                    200: '#EFDCA3',
                    300: '#E3C96F',
                    400: '#D4AF37',
                    500: '#C9A227',
                    600: '#B58A1F',
                    700: '#8F6B18',
                    800: '#6B5013',
                    900: '#4A370D',
                },
            },
            maxWidth: {
                container: '80rem',
            },
            boxShadow: {
                card: '0 1px 2px rgba(14, 27, 69, 0.05), 0 8px 24px -12px rgba(14, 27, 69, 0.18)',
            },
        },
    },

    plugins: [forms],
};
