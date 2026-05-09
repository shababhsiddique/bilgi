// tailwind.config.js
/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
        './resources/**/*.php',
    ],
    theme: {
        extend: {
            colors: {
                brandRed: '#ee3f38ff',
            },
        },
    },
    plugins: [],
};
