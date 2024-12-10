import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue'; // Vue.jsのプラグインをインポート

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
        vue(), // Vue.jsのプラグインを追加
    ],
    server: {
        host: '0.0.0.0',
        hmr: {
            host: 'localhost',
        },
        port: process.env.PORT || 5173,
        watch: {
            usePolling: true,
        },
    },    
    resolve: {
        alias: {
            '@': '/resources/js', // Vueのエイリアス設定
        },
    },
});