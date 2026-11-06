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
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    50: '#e6f0ff',
                    100: '#cce1ff',
                    200: '#99c3ff',
                    300: '#66a4ff',
                    400: '#3386ff',
                    500: '#0067ff', // Main primary color - strong blue
                    600: '#0052cc',
                    700: '#003e99',
                    800: '#002966',
                    900: '#001533',
                },
                secondary: {
                    50: '#f0f9f0',
                    100: '#dcf2df',
                    200: '#b9e5be',
                    300: '#95d89e',
                    400: '#72cb7d',
                    500: '#4fc05d', // Main secondary color - construction green
                    600: '#3f9a4a',
                    700: '#2f7337',
                    800: '#1f4d24',
                    900: '#0f2612',
                },
                accent: {
                    50: '#fff8e6',
                    100: '#ffefcc',
                    200: '#ffd699',
                    300: '#ffbd66',
                    400: '#ffa433',
                    500: '#ff8b00', // Accent color - construction orange
                    600: '#cc6f00',
                    700: '#995300',
                    800: '#663800',
                    900: '#331c00',
                }
            },
            animation: {
                'fade-in-up': 'fadeInUp 0.6s ease-out forwards',
                'scale-in': 'scaleIn 0.5s ease-out forwards',
                'slide-in-left': 'slideInLeft 0.8s ease-out forwards',
                'slide-in-right': 'slideInRight 0.8s ease-out forwards',
            },
            keyframes: {
                fadeInUp: {
                    '0%': {
                        opacity: '0',
                        transform: 'translateY(30px)',
                    },
                    '100%': {
                        opacity: '1',
                        transform: 'translateY(0)',
                    },
                },
                scaleIn: {
                    '0%': {
                        opacity: '0',
                        transform: 'scale(0.9)',
                    },
                    '100%': {
                        opacity: '1',
                        transform: 'scale(1)',
                    },
                },
                slideInLeft: {
                    '0%': {
                        opacity: '0',
                        transform: 'translateX(-50px)',
                    },
                    '100%': {
                        opacity: '1',
                        transform: 'translateX(0)',
                    },
                },
                slideInRight: {
                    '0%': {
                        opacity: '0',
                        transform: 'translateX(50px)',
                    },
                    '100%': {
                        opacity: '1',
                        transform: 'translateX(0)',
                    },
                },
            },
            transitionDuration: {
                '2000': '2000ms',
                '3000': '3000ms',
                '4000': '4000ms',
                '5000': '5000ms',
            },
        },
    },

    plugins: [forms],
};