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
    // Optimize dependencies
    optimizeDeps: {
        include: ['vue', 'vue-router', 'pinia', 'axios'],
        exclude: [],
    },
    // Use esbuild for faster transforms
    esbuild: {
        target: 'es2020',
        legalComments: 'none',
    },
    build: {
        // Optimize build performance
        minify: 'esbuild',
        cssMinify: 'esbuild',
        sourcemap: false,
        // Increase chunk size warning limit
        chunkSizeWarningLimit: 1000,
        // Reduce build overhead
        reportCompressedSize: false,
        // Optimize rollup options
        rollupOptions: {
            output: {
                // Manual chunk splitting for better caching
                manualChunks: (id) => {
                    // Vue ecosystem
                    if (id.includes('node_modules/vue') || id.includes('node_modules/@vue')) {
                        return 'vue-vendor';
                    }
                    // Router and state management
                    if (id.includes('node_modules/vue-router') || id.includes('node_modules/pinia')) {
                        return 'router-vendor';
                    }
                    // Admin-specific vendor
                    if (id.includes('node_modules/quill')) {
                        return 'admin-vendor';
                    }
                    // Other large dependencies
                    if (id.includes('node_modules')) {
                        return 'vendor';
                    }
                },
                // Optimize chunk file names
                chunkFileNames: 'assets/js/[name]-[hash].js',
                entryFileNames: 'assets/js/[name]-[hash].js',
                assetFileNames: 'assets/[ext]/[name]-[hash].[ext]',
            },
        },
        // Use more workers for faster builds
        target: 'es2020',
    },
    // CSS optimization
    css: {
        devSourcemap: false,
    },
    // Server options (for dev, but can help with build caching)
    server: {
        fs: {
            strict: false,
        },
    },
});
