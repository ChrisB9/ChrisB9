#!/usr/bin/env node

import { readFileSync } from 'node:fs';
import { fileURLToPath } from 'node:url';
import { dirname, join } from 'node:path';

const root = join(dirname(fileURLToPath(import.meta.url)), '..');
const problems = [];
const fail = (message) => problems.push(message);

const cv = JSON.parse(readFileSync(join(root, 'src/data/cv.json'), 'utf8'));

const ui = readFileSync(join(root, 'src/i18n/ui.ts'), 'utf8');
const match = ui.match(/export const LOCALES = \[([^\]]+)\]/);
if (!match) {
  fail('Could not read LOCALES from src/i18n/ui.ts');
} else {
  const locales = [...match[1].matchAll(/'([a-z-]+)'/g)].map((m) => m[1]);
  const [fallback] = locales;

  const needsFallback = (value, where) => {
    if (!value) return;
    const own = value[fallback];
    const empty =
      own === undefined || own === null || own === '' || (Array.isArray(own) && !own.length);
    if (empty) fail(`${where} has no ${fallback} value, the fallback for every locale`);
  };

  const unknownLocales = (value, where) => {
    for (const key of Object.keys(value ?? {})) {
      if (!locales.includes(key)) fail(`${where} has an unknown locale "${key}"`);
    }
  };

  for (const [key, value] of Object.entries(cv.labels)) {
    if (key === 'sections') {
      for (const [section, label] of Object.entries(value)) {
        needsFallback(label, `labels.sections.${section}`);
        unknownLocales(label, `labels.sections.${section}`);
      }
      continue;
    }
    needsFallback(value, `labels.${key}`);
    unknownLocales(value, `labels.${key}`);
  }

  needsFallback(cv.contact?.location, 'contact.location');
  unknownLocales(cv.contact?.location, 'contact.location');
  for (const key of ['email', 'site']) {
    if (!cv.contact?.[key]) fail(`contact.${key} is missing`);
  }

  for (const group of cv.skills) {
    needsFallback(group.label, `skills.${group.key}.label`);
    needsFallback(group.items, `skills.${group.key}.items`);
    unknownLocales(group.label, `skills.${group.key}.label`);
    unknownLocales(group.items, `skills.${group.key}.items`);
  }

  const ids = cv.entries.map((e) => e.id);
  const duplicates = ids.filter((id, i) => ids.indexOf(id) !== i);
  if (duplicates.length) fail(`cv.json has duplicate entry ids: ${duplicates.join(', ')}`);

  const hasChildren = (id) => cv.entries.some((e) => e.parent === id);

  for (const entry of cv.entries) {
    const where = `entry "${entry.id}"`;
    unknownLocales(entry.summary, `${where} summary`);
    unknownLocales(entry.bullets, `${where} bullets`);
    if (entry.org) unknownLocales(entry.org, `${where} org`);

    if (entry.parent && !ids.includes(entry.parent)) {
      fail(`${where} names a parent "${entry.parent}" that does not exist`);
    }

    const isPlaceMove = entry.section === 'milestone' && entry.org && entry.coords;
    if (isPlaceMove || hasChildren(entry.id)) continue;

    const summary = entry.summary?.[fallback];
    const bullets = entry.bullets?.[fallback];
    if (!summary && !bullets?.length) {
      fail(`${where} has no ${fallback} text, the fallback for every locale`);
    }
  }
}

if (problems.length) {
  console.error('Content checks failed:\n');
  for (const problem of problems) console.error(`  - ${problem}`);
  process.exit(1);
}
console.log('Content checks passed.');
