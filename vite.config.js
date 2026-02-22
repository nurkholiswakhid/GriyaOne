import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig(({ mode }) => {
    const env = loadEnv(mode, process.cwd(), '');

    // Deteksi ngrok URL dari VITE_NGROK_HOST di .env (opsional)
    // Contoh: VITE_NGROK_HOST=erika-matless-bill.ngrok-free.dev
    const ngrokHost = env.VITE_NGROK_HOST ?? null;

    return {
        plugins: [
            laravel({
                input: ['resources/css/app.css', 'resources/js/app.js'],
                refresh: true,
            }),
            tailwindcss(),
        ],
        server: {
            // Izinkan akses dari host eksternal (ngrok, LAN, dll.)
            host: '0.0.0.0',
            cors: true,
            ...(ngrokHost ? {
                hmr: {
                    host: ngrokHost,
                    protocol: 'wss',
                },
            } : {}),
            watch: {
                ignored: ['**/storage/framework/views/**'],
            },
            allowedHosts: ngrokHost ? [ngrokHost, 'localhost'] : ['localhost'],
        },
    };
});
