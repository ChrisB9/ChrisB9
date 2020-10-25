import {CacheableResponsePlugin} from 'workbox-cacheable-response/CacheableResponsePlugin';
import {CacheFirst} from 'workbox-strategies/CacheFirst';
import {NetworkFirst} from 'workbox-strategies/NetworkFirst';
import {StaleWhileRevalidate} from 'workbox-strategies/StaleWhileRevalidate';
import {ExpirationPlugin} from 'workbox-expiration/ExpirationPlugin';
import {NavigationRoute} from 'workbox-routing/NavigationRoute';
import {precacheAndRoute} from 'workbox-precaching/precacheAndRoute';
import {registerRoute} from 'workbox-routing/registerRoute';
import {isSupported,enable} from 'workbox-navigation-preload/index';

precacheAndRoute(self.__WB_MANIFEST);

const CACHE = 'cbendev-cache';

registerRoute(
  /\.(?:png|jpg|jpeg|svg|webp|avif)$/,
  new CacheFirst({
    cacheName: 'asset-cache',
    matchOptions: {
      ignoreVary: true,
    },
    plugins: [
      new ExpirationPlugin({
        maxEntries: 500,
        maxAgeSeconds: 63072e3,
        purgeOnQuotaError: true,
      }),
      new CacheableResponsePlugin({
        statuses: [0, 200],
      }),
    ],
  }));

registerRoute(
  'manifest.webmanifest',
  new NetworkFirst({
    cacheName: 'webmanifest-cache',
    matchOptions: {
      ignoreVary: true,
    },
    plugins: [
      new ExpirationPlugin({
        maxEntries: 1,
        maxAgeSeconds: 60 * 60 * 24,
        purgeOnQuotaError: true,
      }),
      new CacheableResponsePlugin({
        statuses: [0, 200],
      }),
    ],
  }));

const offlineFallbackPage = 'offline.html';

self.addEventListener('install', async (event) => {
  event.waitUntil(
    caches.open(CACHE)
      .then((cache) => cache.add(offlineFallbackPage)),
  );
});

// navigation preloading
isSupported && enable();

// registerRoute(
//   new RegExp('/*'),
//   new StaleWhileRevalidate({
//     cacheName: CACHE,
//   }),
// );

self.addEventListener('fetch', (event) => {
  if (event.request.mode === 'navigate') {
    event.respondWith((async () => {
      try {
        const preloadResponse = await event.preloadResponse;
        if (preloadResponse) {
          return preloadResponse;
        }
        return await fetch(event.request);
      } catch (error) {
        const cache = await caches.open(CACHE);
        return await cache.match(offlineFallbackPage);
      }
    })());
  }
});

self.addEventListener('message', (event) => {
  if (event.data && event.data.type === 'SKIP_WAITING') {
    self.skipWaiting();
  }
});
