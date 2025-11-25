import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // CSS GLOBAL Y DE PÁGINAS
                'resources/css/style.css',
                'resources/css/app.css',
                'resources/css/blog.css',
                'resources/css/tour.css',
                'resources/css/talleres.css',
                'resources/css/docentes.css',
                'resources/css/cursos.css',

                // JS GENERAL Y DE PÁGINAS
                'resources/js/bootstrap.js',
                'resources/js/script.js',
                'resources/js/app.js',      // <-- solo déjalo si este archivo EXISTE
                // 'resources/js/app.jsx',  // <-- QUITADO
                'resources/js/blog.js',
                'resources/js/tour.js',
                'resources/js/talleres.js',
                'resources/js/docentes.js',
                'resources/js/cursos.js',
            ],
            refresh: [
                'resources/views/**/*.blade.php',
                'app/**/*.php',
            ],
        }),
    ],

    build: {
        minify: 'esbuild',
        cssMinify: true,
        sourcemap: false,
        reportCompressedSize: false,
        chunkSizeWarningLimit: 800,
        rollupOptions: {
            output: {
                // ya no tiene sentido separar vendor-react
                manualChunks: {
                    'vendor-utils': ['axios'],
                },
                entryFileNames: 'assets/[name].js',
                chunkFileNames: 'assets/[name].js',
                assetFileNames: 'assets/[name].[ext]',
            },
        },
    },

    optimizeDeps: {
        // React fuera de aquí también
        include: ['axios'],
        force: false,
    },

    server: {
        host: 'localhost',
        port: 3000,
        cors: true,
        hmr: { overlay: false },
    },

    resolve: {
        alias: {
            '@': '/resources/js',
        },
    },

    css: {
        devSourcemap: false,
    },
});
