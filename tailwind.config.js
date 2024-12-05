import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue', // Vueファイルを含む
        './resources/js/**/*.js', // JavaScriptファイルを含む
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: '#3498db',
                secondary: '#2ecc71',
                darkBg: '#2c3e50',
                darkText: '#ecf0f1',
            },
        },
    },

    darkMode: 'class',  // ダークモードを有効化

    plugins: [
        forms,
        require('@tailwindcss/typography'), // 必要に応じて追加
    ],
};