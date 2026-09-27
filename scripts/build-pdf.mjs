#!/usr/bin/env node

import { readFileSync, mkdirSync } from 'node:fs';
import { execFileSync } from 'node:child_process';
import { statSync } from 'node:fs';
import { fileURLToPath } from 'node:url';
import { dirname, join } from 'node:path';

const root = join(dirname(fileURLToPath(import.meta.url)), '..');

const ui = readFileSync(join(root, 'src/i18n/ui.ts'), 'utf8');
const match = ui.match(/export const LOCALES = \[([^\]]+)\]/);
if (!match) {
  console.error('Could not read LOCALES from src/i18n/ui.ts');
  process.exit(1);
}
const locales = [...match[1].matchAll(/'([a-z-]+)'/g)].map((m) => m[1]);

const FONT_PATHS = [
  process.env.TYPST_FONT_PATH,
  '/usr/share/fonts/noto-cjk',
  '/usr/share/fonts/opentype/noto',
  '/usr/share/fonts/truetype/noto',
].filter((p) => {
  if (!p) return false;
  try {
    return statSync(p).isDirectory();
  } catch {
    return false;
  }
});

const out = join(root, 'public/static');
mkdirSync(out, { recursive: true });

const typeset = (template, locale, name) => {
  const target = join(out, name);
  const args = [
    'compile',
    '--root',
    root,
    '--input',
    `locale=${locale}`,
    ...FONT_PATHS.flatMap((p) => ['--font-path', p]),
    join(root, template),
    target,
  ];
  try {
    execFileSync('typst', args, { stdio: ['ignore', 'inherit', 'pipe'] });
  } catch (error) {
    const detail = error.stderr?.toString().trim();
    console.error(`typst failed for ${name}${detail ? `:\n${detail}` : ''}`);
    process.exit(1);
  }
  console.log(`  public/static/${name}  ${(statSync(target).size / 1024).toFixed(0)} KB`);
};

for (const locale of locales) typeset('pdf/cv.typ', locale, `cv-${locale}.pdf`);

typeset('pdf/rirekisho.typ', 'ja', 'rirekisho.pdf');

console.log(`Typeset ${locales.length} CVs and the rirekisho.`);
