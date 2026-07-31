// import { defineConfig } from 'vite';
// import laravel from 'laravel-vite-plugin';

// export default defineConfig({
//     plugins: [
//         laravel({
//             input: ['resources/css/app.css', 'resources/js/app.js', 'resources/js/english/app.js'],
//             refresh: true,
//         }),
//     ],
// });

// スマホアクセスのため下記に変更しました
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: '0.0.0.0',
        cors: true,
        hmr: {
            host: '10.20.60.100', // あなたのMacのIPアドレス(QQ6F wifi)
        },
    },

    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/english/app.js'
            ],
            refresh: true,
        }),
    ],
});