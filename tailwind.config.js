import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                // La paleta "indigo" de Jetstream pasa a ser el rojo de la marca.
                indigo: {
                    50: '#fdf1f1', 100: '#fbe0e0', 200: '#f7c3c3', 300: '#f19a9a', 400: '#e87272',
                    500: '#e05a5a', 600: '#d64545', 700: '#b13636', 800: '#8f2b2b', 900: '#5d1c1c',
                    950: '#3a1010',
                },
                ink: { 700: '#173536', 800: '#12292a', 900: '#0d1f20' },
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms, typography],
};
