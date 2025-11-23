import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

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
                'resources/js/script.js', // base global
                'resources/js/app.js',
                'resources/js/app.jsx',
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
        react(),
    ],

    // COMPILACIÓN RÁPIDA Y LIMPIA
    build: {
        minify: 'esbuild', // Compilador más veloz que terser
        cssMinify: true,
        sourcemap: false,
        reportCompressedSize: false,
        chunkSizeWarningLimit: 800,
        rollupOptions: {
            output: {
                manualChunks: {
                    'vendor-react': ['react', 'react-dom'],
                    'vendor-utils': ['axios']
                },
                entryFileNames: 'assets/[name].js',
                chunkFileNames: 'assets/[name].js',
                assetFileNames: 'assets/[name].[ext]'
            }
        }
    },

    // OPTIMIZACIÓN DE DEPENDENCIAS
    optimizeDeps: {
        include: ['axios', 'react', 'react-dom'],
        force: false,
    },

    // SERVIDOR LOCAL
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
        devSourcemap: false, // Sin mapas para más rendimiento
    },
});
