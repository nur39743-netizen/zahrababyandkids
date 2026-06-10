import { generateSW } from 'workbox-build';

async function buildServiceWorker() {
  try {
    const buildResult = await generateSW({
      swDest: 'public/service-worker.js',
      globDirectory: 'public',
      globPatterns: [
        'build/**/*.{js,css,svg,png,jpg,webp,ico,json,html}',
        'offline.html',
        'manifest.json',
        'icons/*'
      ],
      navigateFallback: '/offline.html',
      navigateFallbackDenylist: [/^\/api\//],
      runtimeCaching: [
        {
          urlPattern: /\.(?:png|jpg|jpeg|svg|gif|webp)$/,
          handler: 'CacheFirst',
          options: {
            cacheName: 'images-cache',
            expiration: { maxEntries: 60, maxAgeSeconds: 30 * 24 * 60 * 60 }
          }
        },
        {
          urlPattern: /\/(?:api)\//,
          handler: 'NetworkFirst',
          options: {
            cacheName: 'api-cache',
            networkTimeoutSeconds: 10,
            expiration: { maxEntries: 50, maxAgeSeconds: 24 * 60 * 60 }
          }
        }
      ],
      skipWaiting: true,
      clientsClaim: true
    });

    console.log('Workbox build complete:', buildResult);
  } catch (err) {
    console.error('Workbox build failed:', err);
    process.exit(1);
  }
}

buildServiceWorker();
