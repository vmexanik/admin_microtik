import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
                'resources/js/update_router.js',
                'resources/js/user_router.js',
                'resources/js/check_router_status.js'
            ],
            refresh: true,
        }),
    ],
});
