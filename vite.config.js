import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.jsx'],
            refresh: true,
        }),
        react(),
    ],
    server: {
        hmr: {
            host: 'localhost'
        },
        proxy: {
            '/get-snap-token': 'http://localhost:8000',
        }
    },
    // =======================================================
    // Tambahkan bagian 'define' ini
    // =======================================================
    define: {
        // Ini akan membuat process.env.VITE_API_BASE_URL tersedia di frontend
        // Nilainya diambil dari APP_URL di .env Laravel Anda, ditambah '/api'
        'process.env.VITE_API_BASE_URL': JSON.stringify(process.env.APP_URL ? process.env.APP_URL + '/api' : 'http://127.0.0.1:8000/api'),
    },
    // =======================================================
});