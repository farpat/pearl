import {defineConfig} from 'vite';
import liveReload from 'vite-plugin-live-reload';
import config from './config';
import legacy from '@vitejs/plugin-legacy';
import dynamicImportVars from '@rollup/plugin-dynamic-import-vars';

// https://vitejs.dev/config/
export default defineConfig({
    plugins: [
        liveReload(config.refresh.map(path => '../' + path)),
        legacy(),
    ],
    root: './assets',
    base: '/assets/',
    build: {
        polyfillDynamicImport: true,
        outDir: '../' + config.output,
        assetsDir: '',
        manifest: true,
        rollupOptions: {
            plugins: [dynamicImportVars()],
            output: {
                manualChunks: undefined, // Désactive la séparation du vendor
            },
            input: config.entry,
        },
    },
});
