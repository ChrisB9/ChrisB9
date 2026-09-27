import { defineCollection } from 'astro:content';
import { z } from 'zod';
import { glob, file } from 'astro/loaders';

const pages = defineCollection({
  loader: glob({ pattern: '**/*.md', base: './src/content/pages' }),
  schema: z.object({
    title: z.string(),
    description: z.string(),
    sections: z.array(z.enum(['timeline', 'stack', 'work'])).default([]),
    portrait: z.boolean().default(false),
    noindex: z.boolean().default(false),

    draft: z.boolean().default(false),
    nav: z.number().optional(),
  }),
});

const translated = z.object({ en: z.string(), de: z.string(), ja: z.string() }).partial();
const translatedList = z
  .object({ en: z.array(z.string()), de: z.array(z.string()), ja: z.array(z.string()) })
  .partial();

const cv = defineCollection({
  loader: file('./src/data/cv.json', {
    parser: (text) => JSON.parse(text).entries,
  }),
  schema: z.object({
    id: z.string(),
    section: z.enum(['employment', 'education', 'freelance', 'opensource', 'project', 'milestone']),

    parent: z.string().nullable().default(null),
    from: z.string().nullable(),
    to: z.string().nullable(),
    org: translated.nullable().default(null),
    url: z.url().nullable(),
    onTimeline: z.boolean().default(false),

    coords: z.tuple([z.number(), z.number()]).nullable().default(null),
    summary: translated.default({}),
    bullets: translatedList.default({}),
  }),
});

export const collections = { pages, cv };
