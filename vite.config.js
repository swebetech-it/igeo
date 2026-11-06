import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/website-new.css',
                'resources/css/website-radical.css',
                'resources/css/asymmetrical-layout.css',
                'resources/css/modern-landing.css',
                'resources/js/app.js'
            ],
            refresh: true,
        }),
    ],
});