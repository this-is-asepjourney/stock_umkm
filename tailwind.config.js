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
                sans: ['Plus Jakarta Sans', 'Figtree', ...defaultTheme.fontFamily.sans],
                mono: ['JetBrains Mono', ...defaultTheme.fontFamily.mono],
            },
            colors: {
                primary: '#6366F1',
                success: '#10B981',
                warning: '#F59E0B',
                danger: '#EF4444',
            },
            keyframes: {
                'aj-shimmer': {
                    '100%': { transform: 'translateX(100%)' },
                },
            },
            animation: {
                'aj-shimmer': 'aj-shimmer 2s infinite',
            },
        },
    },

    plugins: [forms],
};