import {defineConfig} from 'vite';
import liveReload from 'vite-plugin-live-reload';
import config from './config';
import legacy from '@vitejs/plugin-legacy';

// https://vitejs.dev/config/
export default defineConfig({
    plugins: [
        liveReload(config.refresh.map(path => '../' + path)),
        legacy({targets: ['defaults', 'not IE 11']}),
    ],
    root: './assets',
    base: '/assets/',
    build: {
        outDir: '../' + config.output,
        assetsDir: '',
        manifest: true,
        rollupOptions: {
            output: {
                manualChunks: undefined, // Désactive la séparation du vendor
            },
            input: config.entry,
        },
    },
});
