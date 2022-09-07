import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import Swal from 'sweetalert2';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
                'resources/js/sweetalert2.js',
                'resources/js/export.js',
            ],
            refresh: true,
        }),
    ],
});
