# Setup

Astro + Tailwind + TypeScript → static files. No PHP, no database, no SSR.
Tooling from [mise](https://mise.jdx.dev); nothing installed by hand.

```sh
mise install          # Node, hk, pkl, actionlint, zizmor, typst, age
mise run install      # npm ci + git hooks
mise run dev          # http://localhost:4321
```

`node_modules/.bin` is on PATH via `mise.toml`; `astro`, `prettier` etc. need no `npx`.

## Tasks

| Task                 | Result                        | TTY |
| -------------------- | ----------------------------- | --- |
| `mise run dev`       | dev server                    | no  |
| `mise run build`     | PDFs, then `dist/`            | no  |
| `mise run build-pdf` | PDFs only                     | no  |
| `mise run preview`   | serve `dist/`                 | no  |
| `mise run check`     | types across `.astro` + `.ts` | no  |
| `mise run lint`      | all hk checks, whole repo     | no  |
| `mise run fmt`       | format                        | no  |
| `mise run todo`      | remaining copy placeholders   | no  |
| `mise run personal`  | edit `pdf/personal.age`       | yes |
| `mise run rirekisho` | filled 履歴書 → `out/`        | yes |

## Checks

[hk](https://hk.jdx.dev), configured in `hk.pkl`. Hooks installed by `mise run install`.

| Hook       | Behaviour                    |
| ---------- | ---------------------------- |
| pre-commit | fix, stage, fail on the rest |
| pre-push   | re-check, no fixing          |
| CI         | `hk check --all`, same set   |

- `prettier`: formatting
- `astro-check`: types
- `content`: `scripts/check-content.mjs`
- `actionlint`, `zizmor`: workflows
- merge markers, private keys, oversized files

Bypass: `HK=0 git commit`.

`check-content.mjs` reads `LOCALES` from `src/i18n/ui.ts`, then asserts on `src/data/cv.json`:

- every translated leaf carries the fallback locale
- no leaf names an unknown locale
- entry ids unique
- every `parent` exists
- `contact.email`, `contact.site` present

Exempt from needing text: place milestones (`section: milestone` + `org` + `coords`), companies with children.

## Layout

```
mise.toml                     tools, tasks
hk.pkl                        commit/push checks
pdf/                          Typst sources → see pdf/README.md
scripts/                      repo tooling
src/
├── content/pages/<locale>/   markdown, one file per page per language
├── data/                     cv.json, projects.json, stack.json, visited.json, site.ts
├── i18n/                     ui.ts (strings), utils.ts (helpers)
├── lib/                      cv.ts (data access), world.ts (projection, codes)
├── components/               Header, Footer, Journey, MoveMap, WorldMap, WorkList, TechStack
├── layouts/Base.astro        head, header, footer
├── pages/[locale]/           routes
├── scripts/                  client-side code
└── styles/global.css         tokens, markdown, print
```

## Languages

`en`, `de`, `ja`, all prefixed. `/` reads `navigator.languages` → falls back to `en`.

| Source                            | Missing key/file              |
| --------------------------------- | ----------------------------- |
| `src/i18n/ui.ts` (typed)          | build error                   |
| `src/content/pages/<locale>/*.md` | English + untranslated notice |
| `cv.json` leaves                  | English, per value            |

Adding a locale: `LOCALES` in `ui.ts` → dictionary → `cv.json` leaves → `astro.config.mjs`.

## Page frontmatter

```yaml
---
title: 'About me'
description: 'Search results and link previews'
sections: [timeline] # timeline | stack | work
portrait: true
nav: 1 # header position; omit to hide
noindex: true # omit unless it should stay out of search
draft: true # omit unless the route should skip it
---
```

Body is plain markdown. `{age}` → computed at build from `site.birth`.

## cv.json

One file: `labels`, `contact`, `rirekisho`, `intro`, `skills`, `entries`.
Structure nests; **a translated value is always the leaf**.

```json
{
  "id": "easybill-freelance",
  "section": "employment",
  "parent": "easybill",
  "from": "2025",
  "to": null,
  "onPdf": true,
  "summary": { "en": "Freelance", "de": "Freelance", "ja": "フリーランス" },
  "bullets": { "en": ["…"], "de": ["…"], "ja": ["…"] }
}
```

| Field        | Meaning                                                                      |
| ------------ | ---------------------------------------------------------------------------- |
| `section`    | `employment`, `education`, `freelance`, `opensource`, `project`, `milestone` |
| `parent`     | one engagement of that company; renders nested                               |
| `summary`    | job title / qualification / one-line description / milestone                 |
| `onTimeline` | show on the about-page timeline                                              |
| `onPdf`      | `false` keeps it on the site, off the printed CV                             |
| `coords`     | `[lat, lon]`, place milestones only                                          |

- company with children carries the shared description; children carry title + dates
- `src/data/` not `src/content/`: one JSON file, not a folder of documents
- `entries` is still a collection; `content.config.ts` points `file()` at this path
- `labels`, `contact`, `intro`, `skills` imported directly by `src/lib/cv.ts`

## visited.json

```json
{ "current": "JP", "lived": ["DE", "JP"], "visited": ["AT", "AU", "…"] }
```

- ISO 3166-1 alpha-2 only
- `lived` strong colour, `visited` soft, caption counts both, `current` named
- names via `Intl.DisplayNames` → all three locales automatically
- too small for Natural Earth 110m (Singapore, Hong Kong, Vatican, Monaco, Liechtenstein) → marker table in `src/lib/world.ts`
- neither drawable nor markable → build warning
- Antarctica excluded: +⅓ height, nobody counts it
- generated at build from `world-atlas`, inlined SVG, ~26 KB, no runtime request

## projects.json

| Field      | Meaning                                        |
| ---------- | ---------------------------------------------- |
| `url`      | where it can be seen or used                   |
| `source`   | where the code is                              |
| `client`   | `true` → client filter; `false` → own projects |
| `active`   | which group                                    |
| `pinned`   | top of its group                               |
| `hidden`   | omitted entirely                               |
| `internal` | local route instead of `url`                   |

- title links `url` → falls back to `source`
- separate "source" link only when both exist and differ
- neither → translated "not public" marker
- "Own projects" (`client: false`) ≠ open source (`source` present)
- `title`/`description` are `en`+`de`+`ja`

## stack.json

- group carries `label` (same in every language) or `key` (→ `stack.<key>` in `ui.ts`)
- flat on purpose; nested Frameworks/CMS/Testing read as an inventory
- spoken languages appended from `cv.json` `skills.languages`, names only (text before `:`)

## Theming

- dark only: one palette, no toggle, no `prefers-color-scheme` branch
- tokens in `global.css`, used via Tailwind (`bg-surface`, `text-ink-soft`, `border-line`)
- hairlines and whitespace, not cards; plain links, not buttons; mono for metadata
- violet dot marks the current item
- `@media print` redefines the same tokens to light + `@page` A4 16/14mm + reveals `.print-only`

## PDFs

`pdf/cv.typ` (3 locales) and `pdf/rirekisho.typ` (ja). Typeset from `cv.json`, not printed from the site.

→ **[pdf/README.md](pdf/README.md)** for files, inputs, fonts, field mapping and the encrypted personal data.

## Email address

No joinable address reaches the HTML. `src/lib/email.ts` splits `site.email` and reverses each half; `Base.astro` rejoins them client-side.

| Placeholder                        | Rendered as                              | Used by                         |
| ---------------------------------- | ---------------------------------------- | ------------------------------- |
| `<a data-email-u data-email-d>`    | `href` set in place                      | social links, start page        |
| `<span data-email-u data-email-d>` | replaced by an `<a>` showing the address | CV print line                   |
| `{email}` in markdown              | the span form                            | imprints, via `[...slug].astro` |

- `data-email-u="tcatnoc"`, `data-email-d="oc.nebc"`
- no-JS fallback: `<noscript>` with the `(at)` / `(punkt)` form
- defeats regex harvesters; a scraper that runs JS still gets it
- PDFs carry the plain address on purpose

## Work page

- one filterable list; filter reflected in the URL hash → `/work#client` is shareable

## About page

- one chronological timeline, newest first; a move is an entry, not a heading
- move → `MoveMap`: cropped window on the start-page projection, dotted arc
- `Journey.astro` emits the geometry once into a hidden `<defs>`; each map is a `<use>`
- crop sized from hop distance, with a floor for 110m coastline resolution
- needs `coords`; without them the entry renders mapless

## Accessibility

Reading mode (`html.reading`), toggled in the header and on the start page, remembered per browser, applied before first paint.

- root font size → 112.5%; everything else in rem
- looser prose leading, lifted muted colours

| Against canvas | Default | Reading |
| -------------- | ------- | ------- |
| body           | n/a     | 18.97:1 |
| secondary      | n/a     | 13.74:1 |
| muted          | n/a     | 10.57:1 |
| links          | 4.79:1  | 9.67:1  |
| hairlines      | n/a     | 3.66:1  |

`--color-on-primary`: use `text-on-primary` with `bg-primary`, never `text-white`. White on violet is 3.96:1, and 1.96:1 in reading mode.

## Not routed

| Section    | Mechanism                         | Restore      |
| ---------- | --------------------------------- | ------------ |
| Playground | `src/pages/[locale]/_playground/` | drop the `_` |

Also: Tic Tac Toe is `"hidden": true` in `projects.json`; nav entry in `Header.astro`.

## Deploying

Built once in CI, shipped as an image. The host pulls and restarts; it never builds.

```
mise run build            dist/
docker build .            dist/ -> nginx image
ghcr.io/chrisb9/cben-dev  pushed on main
/var/www/website          compose file scp'd, then pull && up -d
```

| Tag            | Example              |
| -------------- | -------------------- |
| `latest`       | every push to `main` |
| `<date>.<run>` | `2026.09.28.147`     |
| `sha-<short>`  | `sha-a1b2c3d`        |

`deploy/docker-compose.yml` is copied to `/var/www/website` by the deploy job, so the repo is the source of truth. `APP_VERSION` pins a tag, default `latest`.

### Image

- base `nginxinc/nginx-unprivileged:1.29-alpine`, uid 101, listens on 8080
- `.dockerignore` is deny-all except `dist/` and `deploy/nginx.conf`
- OCI labels from `--build-arg VERSION/REVISION/CREATED`
- `/healthz` plain-text 200, wired to `HEALTHCHECK`
- needs a prior `mise run build`; the Dockerfile copies `dist/`, it does not build it

### nginx

- `try_files $uri $uri.html $uri/index.html` plus `absolute_redirect off` serves `/en/about` with no redirect, matching `trailingSlash: 'never'`
- `/_astro/` immutable for a year; `/static/` one hour; HTML `no-cache`
- gzip, `nosniff`, `Referrer-Policy`, `frameDeny`, `server_tokens off`

### Traefik

Joins the existing external network from freelance-hub, alongside shiritori. HSTS is set there, not in the image, because TLS terminates at Traefik.

The compose project name comes from the directory, so `/var/www/website` keeps it separate from the other stacks on the host.

| Router    | Rule             | Result                    |
| --------- | ---------------- | ------------------------- |
| `cben`    | `Host(cben.dev)` | the site, port 8080       |
| `cben-co` | `Host(cben.co)`  | 301 to `https://cben.dev` |

Compose is `version: "2.2"` and the deploy calls `docker-compose`: the host runs compose v1, which rejects a version-less file.

### Secrets

`SSH_PRIVATE_KEY`, `SSH_HOST`, `SSH_USER`, `SSH_PORT`, and optionally `SSH_KNOWN_HOSTS` (without it the deploy falls back to `ssh-keyscan`, which trusts on first use). GHCR push uses the built-in `GITHUB_TOKEN`. The host is already logged in to ghcr.io for shiritori and freelance-hub, so the package can stay private.
