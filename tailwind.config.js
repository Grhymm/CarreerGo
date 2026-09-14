import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    DEFAULT: '#0F4C3A',
                    50: '#F3F6F5',
                    100: '#E2EAE7',
                    200: '#BCCDC8',
                    300: '#95B0A8',
                    400: '#5C8579',
                    500: '#35695A',
                    600: '#0F4C3A',
                    700: '#0D4031',
                    800: '#0A3427',
                    900: '#08281E',
                },
                secondary: {
                    DEFAULT: '#C89B3C',
                    50: '#FCFAF5',
                    100: '#F8F3E8',
                    200: '#F0E3C8',
                    300: '#E7D3A9',
                    400: '#DABB7A',
                    500: '#D1AB5B',
                    600: '#C89B3C',
                    700: '#A88232',
                    800: '#886929',
                    900: '#68511F',
                },
                tertiary: {
                    DEFAULT: '#14607A',
                    50: '#F3F7F8',
                    100: '#E3ECEF',
                    200: '#BDD2DA',
                    300: '#98B9C4',
                    400: '#5F93A5',
                    500: '#3A798F',
                    600: '#14607A',
                    700: '#115166',
                    800: '#0E4153',
                    900: '#0A323F',
                },
            },
        },
    },

    plugins: [forms],
};
