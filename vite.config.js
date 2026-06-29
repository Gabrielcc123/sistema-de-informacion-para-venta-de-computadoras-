import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            buildDirectory: 'build', // Indica a Laravel que busque aquí
        }),
        tailwindcss(),
    ],
    // ESTO ES LO QUE OBLIGARÁ A VITE A ESCRIBIR BIEN LAS RUTAS
    base: process.env.APP_ENV === 'production' ? '/build/' : '/',
});
