import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    server: {
        host: '0.0.0.0',  // 外部からのアクセスを許可(Herokuは外部アクセスを許可するため、0.0.0.0 に設定するのは問題なし)
        port: process.env.PORT || 5173,  // HerokuでPORTが設定されていればそのポートを使用。設定されていなければ5173
    },
});