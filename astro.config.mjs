// @ts-check
import { defineConfig, fontProviders } from 'astro/config';
import sitemap from '@astrojs/sitemap';
import tailwindcss from '@tailwindcss/vite';

const LOCALES = ['en', 'de', 'ja'];

export default defineConfig({
  site: 'https://cben.dev',
  trailingSlash: 'never',
  i18n: {
    locales: LOCALES,
    defaultLocale: 'en',
    routing: {
      prefixDefaultLocale: true,
    },
  },

  fonts: [
    {
      provider: fontProviders.google(),
      name: 'Inter',
      cssVariable: '--font-inter',
      weights: [300, 400, 500, 600, 700],
      styles: ['normal'],
      subsets: ['latin', 'latin-ext'],
      fallbacks: [
        'Hiragino Kaku Gothic ProN',
        'Noto Sans JP',
        'Yu Gothic',
        'system-ui',
        'sans-serif',
      ],
    },
    {
      provider: fontProviders.google(),
      name: 'JetBrains Mono',
      cssVariable: '--font-jetbrains',
      weights: [400, 500],
      styles: ['normal'],
      subsets: ['latin', 'latin-ext'],
      fallbacks: ['ui-monospace', 'SFMono-Regular', 'Menlo', 'monospace'],
    },
  ],
  integrations: [
    sitemap({
      i18n: { defaultLocale: 'en', locales: { en: 'en', de: 'de', ja: 'ja' } },
      filter: (page) => !page.includes('/imprint'),
    }),
  ],
  vite: { plugins: [tailwindcss()] },
});
