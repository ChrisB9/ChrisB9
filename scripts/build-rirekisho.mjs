#!/usr/bin/env node

import { execFileSync } from 'node:child_process';
import { writeFileSync, existsSync, rmSync, mkdirSync, statSync } from 'node:fs';
import { createInterface } from 'node:readline/promises';
import { fileURLToPath } from 'node:url';
import { dirname, join } from 'node:path';

const root = join(dirname(fileURLToPath(import.meta.url)), '..');
const encrypted = join(root, 'pdf/personal.age');
const scratch = join(root, 'pdf/.personal.json');

if (!existsSync(encrypted)) {
  console.error('No pdf/personal.age yet. Run `mise run personal` to create it.');
  process.exit(1);
}

const FONT_PATHS = ['/usr/share/fonts/noto-cjk', '/usr/share/fonts/opentype/noto'].filter((p) => {
  try {
    return statSync(p).isDirectory();
  } catch {
    return false;
  }
});

const askBlock = async (rl, prompt) => {
  console.log(`\n${prompt}`);
  console.log('(end with an empty line, or press enter straight away to leave it blank)');
  const lines = [];
  for (;;) {
    const line = await rl.question('> ');
    if (line === '') break;
    lines.push(line);
  }
  return lines.join('\n');
};

try {
  console.log('Decrypting pdf/personal.age. age will ask for your passphrase.\n');
  const plain = execFileSync('age', ['--decrypt', encrypted], {
    stdio: ['inherit', 'pipe', 'inherit'],
  });
  const personal = JSON.parse(plain.toString());

  const rl = createInterface({ input: process.stdin, output: process.stdout });
  const note = await askBlock(rl, '本人希望記入欄: anything to say to this company?');
  const company = await rl.question('\nCompany name, for the filename (optional): ');
  rl.close();

  if (note !== '') personal.note = note;
  writeFileSync(scratch, JSON.stringify(personal), { mode: 0o600 });

  const slug = company
    .trim()
    .replaceAll(/[^\p{L}\p{N}]+/gu, '-')
    .replace(/^-|-$/g, '');
  const out = join(root, 'out');
  mkdirSync(out, { recursive: true });
  const target = join(out, slug ? `履歴書-${slug}.pdf` : '履歴書.pdf');

  execFileSync(
    'typst',
    [
      'compile',
      '--root',
      root,
      '--input',
      'locale=ja',
      '--input',
      'personal=.personal.json',
      ...FONT_PATHS.flatMap((p) => ['--font-path', p]),
      join(root, 'pdf/rirekisho.typ'),
      target,
    ],
    { stdio: ['ignore', 'inherit', 'inherit'] },
  );

  console.log(
    `\nWrote ${target.replace(root + '/', '')} (${(statSync(target).size / 1024).toFixed(0)} KB).`,
  );
  console.log('This one has your personal data on it. It is not in dist/ and not published.');
} finally {
  rmSync(scratch, { force: true });
}
