import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';
import path from 'path';

export default defineConfig({
    plugins: [react()],
    root: 'resources/js/security-dashboard',
    build: {
        outDir: path.resolve(__dirname, 'public/assets/security-dashboard'),
        emptyOutDir: true,
        manifest: true,
        chunkSizeWarningLimit: 1000,
        rollupOptions: {
            input: path.resolve(__dirname, 'resources/js/security-dashboard/main.tsx'),
            output: {
                manualChunks(id) {
                    if (id.includes('node_modules')) {
                        if (id.includes('echarts') || id.includes('zrender')) {
                            return 'echarts';
                        }
                        if (id.includes('react') || id.includes('react-dom') || id.includes('scheduler')) {
                            return 'react-vendor';
                        }
                        return 'vendor';
                    }
                }
            }
        },
    },
    server: {
        strictPort: true,
        port: 5173,
        origin: 'http://localhost:5173',
    },
});
