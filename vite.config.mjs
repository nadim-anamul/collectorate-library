import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
    ],
    build: {
        // Production optimizations
        minify: "terser",
        terserOptions: {
            compress: {
                drop_console: true,
                drop_debugger: true,
            },
        },
        rollupOptions: {
            output: {
                manualChunks: {
                    vendor: ["alpinejs"],
                },
            },
        },
        // Optimize CSS
        cssCodeSplit: true,
        // Asset optimization
        assetsInlineLimit: 4096,
        // Source maps for production debugging
        sourcemap: false,
    },
    // Development optimizations
    server: {
        hmr: {
            host: "localhost",
        },
    },
    // CSS optimization
    css: {
        devSourcemap: true,
    },
});
