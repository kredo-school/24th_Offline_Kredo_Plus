import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: '0.0.0.0',
        cors: true,
        hmr: {
            host: '10.30.202.86', // MacのIPアドレス
        },
    },

    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/english/app.js',
                'resources/css/earth/style.css',
                'resources/js/earth/main.js',
                'resources/js/admin.js',
            ],
            refresh: true,
        }),
    ],
});
