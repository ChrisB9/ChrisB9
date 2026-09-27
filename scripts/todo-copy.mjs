#!/usr/bin/env node

import { readFileSync } from 'node:fs';
import { globSync } from 'node:fs';
import { fileURLToPath } from 'node:url';
import { dirname, join, relative } from 'node:path';

const root = join(dirname(fileURLToPath(import.meta.url)), '..');
const files = globSync('src/**/*.{ts,json}', { cwd: root });

let total = 0;
for (const file of files.sort()) {
  const lines = readFileSync(join(root, file), 'utf8').split('\n');
  const hits = lines
    .map((line, i) => ({ line: line.trim(), n: i + 1 }))
    .filter(({ line }) => line.includes('TODO'));
  if (!hits.length) continue;
  console.log(`\n${relative('.', file)}`);
  for (const { line, n } of hits) {
    console.log(`  ${String(n).padStart(4)}  ${line.slice(0, 96)}`);
    total++;
  }
}

console.log(
  total ? `\n${total} placeholder${total === 1 ? '' : 's'} left to write.` : '\nNothing left.',
);
