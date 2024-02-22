import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue'

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/auth.css',
                'resources/js/auth.css',
                '_frontend/app.js',
            ],
            refresh: true,
        }),
        vue()
    ],
});
