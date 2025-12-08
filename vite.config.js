import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/public/app.js',
                'resources/js/admin/app.js',
            ],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, './resources/js'),
            '@components': path.resolve(__dirname, './resources/js/public/components'),
            '@pages': path.resolve(__dirname, './resources/js/public/pages'),
            '@layouts': path.resolve(__dirname, './resources/js/public/layouts'),
            '@composables': path.resolve(__dirname, './resources/js/public/composables'),
            '@stores': path.resolve(__dirname, './resources/js/public/stores'),
        },
    },
});
