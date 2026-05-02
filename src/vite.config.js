import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    server: {
        host: true,
        origin: 'http://localhost:5173',
        cors: true,
        watch: {
            usePolling: true,
        },
        hmr: false,
    },
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: false,
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
});
