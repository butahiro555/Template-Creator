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
        host: true,
        hmr: {
            host: 'localhost',
        },
        port: process.env.PORT || 5173, // HerokuなどでPORTが設定されていればそのポートを使用
        watch: {
            usePolling: true, // Docker環境でのファイル変更検知を安定させるため
        },
    },
    resolve: {
        alias: {
            '@': '/resources/js', // Vueのエイリアス設定
        },
    },
});