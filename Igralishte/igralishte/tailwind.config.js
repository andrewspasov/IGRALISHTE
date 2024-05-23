import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        './resources/js/profile.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
            scale: {
                15: "0.22",
            },
            colors: {
                'custom-pink': '#ffdddd',
                'custom-olive': '#8A8328',
              },
              screens: {
                'xxs': '320px',
              },
        },
    },

    plugins: [forms],
};
