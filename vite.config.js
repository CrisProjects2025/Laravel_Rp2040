import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({

    server: {
        host: '10.185.66.24', // ← Your desired IP here
        port: 8000,             // Optional: change port if needed
        strictPort: true,
    },

    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/door-control.js' // ✅ Add this line
            ],
            refresh: true,
        }),
    ],
});

