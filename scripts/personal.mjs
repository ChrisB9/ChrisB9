#!/usr/bin/env node

import { execFileSync } from 'node:child_process';
import { readFileSync, writeFileSync, existsSync, rmSync, mkdtempSync, chmodSync } from 'node:fs';
import { fileURLToPath } from 'node:url';
import { dirname, join } from 'node:path';
import { tmpdir } from 'node:os';

const root = join(dirname(fileURLToPath(import.meta.url)), '..');
const encrypted = join(root, 'pdf/personal.age');
const template = join(root, 'pdf/personal.example.json');

const editor = process.env.VISUAL || process.env.EDITOR;
if (!editor) {
  console.error('Set $EDITOR (or $VISUAL) to the editor you want to use.');
  process.exit(1);
}

const dir = mkdtempSync(join(tmpdir(), 'cben-personal-'));
const scratch = join(dir, 'personal.json');

try {
  if (existsSync(encrypted)) {
    console.log('Decrypting pdf/personal.age. age will ask for your passphrase.\n');
    const plain = execFileSync('age', ['--decrypt', encrypted], {
      stdio: ['inherit', 'pipe', 'inherit'],
    });
    writeFileSync(scratch, plain, { mode: 0o600 });
  } else {
    console.log('No pdf/personal.age yet, starting from the template.\n');
    writeFileSync(scratch, readFileSync(template), { mode: 0o600 });
  }
  chmodSync(scratch, 0o600);

  const before = readFileSync(scratch, 'utf8');
  execFileSync(editor, [scratch], { stdio: 'inherit', shell: false });
  const after = readFileSync(scratch, 'utf8');

  if (after === before) {
    console.log('\nNo changes; leaving pdf/personal.age as it was.');
    process.exit(0);
  }

  try {
    JSON.parse(after);
  } catch (error) {
    console.error(`\nThat is not valid JSON, so nothing was written:\n  ${error.message}`);
    process.exit(1);
  }

  console.log('\nRe-encrypting. age will ask for the passphrase to use.\n');
  execFileSync('age', ['--passphrase', '--output', encrypted, scratch], { stdio: 'inherit' });
  console.log('\nWrote pdf/personal.age. It is safe to commit.');
} finally {
  rmSync(dir, { recursive: true, force: true });
}
