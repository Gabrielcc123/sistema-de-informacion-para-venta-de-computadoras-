
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            // Agrega esto para que los assets tengan la ruta correcta en producción
            buildDirectory: 'build', 
        }),
        tailwindcss(),
    ],
    // Asegúrate de definir el base path para producción
    base: '/build/', 
});
