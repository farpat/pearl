import { defineConfig } from 'vite'
import reactRefresh from '@vitejs/plugin-react-refresh'
import liveReload from 'vite-plugin-live-reload'
import config from './config'

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [reactRefresh(), liveReload(config.refresh.map(path => '../' + path))],
  root: './assets',
  base: '/assets/',
  build: {
    outDir: '../' + config.output,
    assetsDir: '',
    manifest: true,
    rollupOptions: {
      output: {
        manualChunks: undefined // Désactive la séparation du vendor
      },
      input: config.entry
    }
  }
})
