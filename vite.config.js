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
                'resources/js/vue/app.js',
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
            '@components': path.resolve(__dirname, './resources/js/vue/components'),
            '@pages': path.resolve(__dirname, './resources/js/vue/pages'),
            '@layouts': path.resolve(__dirname, './resources/js/vue/layouts'),
            '@composables': path.resolve(__dirname, './resources/js/vue/composables'),
            '@stores': path.resolve(__dirname, './resources/js/vue/stores'),
        },
    },
});
