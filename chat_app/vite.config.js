import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import { defineConfig } from 'vite';
import { fileURLToPath, URL } from "node:url";

export default defineConfig({
    server: {
        host: true,
        port: 8088,
        hmr: {
            host: 'localhost',
        }
    },
    plugins: [
        laravel({
            input: [
                'resources/css/chat.css',
                'resources/js/app.js',
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
            vue: 'vue/dist/vue.esm-bundler.js',
            '@': fileURLToPath(new URL('./resources/js', import.meta.url)),
        },
    },
});
