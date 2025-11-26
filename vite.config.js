import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                
                'resources/css/driver_register.css', 
                'resources/js/driver_register.js',
            ],
            refresh: true,
        }),
    ],
});