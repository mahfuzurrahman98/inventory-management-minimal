/** @type {import('tailwindcss').Config} */

import vue from '@vitejs/plugin-vue';

export default {
    content: ['./index.html', './src/**/*.{js,ts,jsx,tsx,vue,html}'],
    theme: {
        extend: {},
    },
    plugins: [vue()],
};
