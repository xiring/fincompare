import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.ts',
                'resources/js/public/app.ts',
                'resources/js/admin/app.ts',
            ],
            refresh: true,
        }),
        vue({
            script: {
                defineModel: true,
                propsDestructure: true,
            },
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
            '@admin': path.resolve(__dirname, './resources/js/admin'),
            '@public': path.resolve(__dirname, './resources/js/public'),
            '@components': path.resolve(__dirname, './resources/js/public/components'),
            '@pages': path.resolve(__dirname, './resources/js/public/pages'),
            '@layouts': path.resolve(__dirname, './resources/js/public/layouts'),
            '@composables': path.resolve(__dirname, './resources/js/public/composables'),
            '@stores': path.resolve(__dirname, './resources/js/public/stores'),
        },
    },
    build: {
        // Optimize build performance
        minify: 'esbuild',
        cssMinify: true,
        sourcemap: false,
        // Increase chunk size warning limit
        chunkSizeWarningLimit: 1000,
        // Optimize rollup options
        rollupOptions: {
            output: {
                // Manual chunk splitting for better caching
                manualChunks: {
                    'vue-vendor': ['vue', 'vue-router', 'pinia'],
                    'admin-vendor': ['quill'],
                },
            },
        },
    },
});
