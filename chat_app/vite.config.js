import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import { defineConfig } from 'vite';
import { fileURLToPath, URL } from "node:url";

export default defineConfig({
  server: {
    host: true,
    port: 8088,
    // host: '0.0.0.0',
    hmr: {
      host: 'localhost',
      // overlay: false,
      // clientPort: 3077,
    },
    proxy: {
      // '/': {
      //   target: 'http://localhost:8077',
      //   // changeOrigin: true,
      //   // secure: false,
      //   // logLevel: 'debug'
      // },
      // '/socket.io': {
      //   target: 'http://localhost:3077',
      //   changeOrigin: true,
      //   ws: true,
      // },
    }
    // proxy: {
    //   '/ok': {
    //     target: 'http://chat-vue-nodejs',
    //     changeOrigin: true,
    //     secure: false,
    //     // logLevel: 'debug'
    //   },
    // }
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

