# pdf/

Typst sources for the CV and 履歴書. Data: `../src/data/cv.json`.

## Files

| File                    | Tracked | Role                                       |
| ----------------------- | ------- | ------------------------------------------ |
| `cv.typ`                | yes     | Western CV, one per locale                 |
| `rirekisho.typ`         | yes     | 履歴書, Japanese only                      |
| `lib.typ`               | yes     | locale, data access, theme, date ranges    |
| `portrait.jpg`          | yes     | 512×630, from `scripts/build-images.sh`    |
| `personal.json`         | yes     | `{}`; blank-field default                  |
| `personal.example.json` | yes     | schema, no values                          |
| `personal.age`          | yes     | age-encrypted personal data                |
| `.personal.json`        | no      | decrypted plaintext, deleted after a build |

## Commands

| Command              | TTY | Output                                                         |
| -------------------- | --- | -------------------------------------------------------------- |
| `mise run build-pdf` | no  | `public/static/cv-<locale>.pdf`, `public/static/rirekisho.pdf` |
| `mise run build`     | no  | above, then `astro build`                                      |
| `mise run personal`  | yes | `pdf/personal.age`                                             |
| `mise run rirekisho` | yes | `out/履歴書-<company>.pdf`                                     |

## Inputs

| `--input`  | Values                   | Default         |
| ---------- | ------------------------ | --------------- |
| `locale`   | `en`, `de`, `ja`         | `en`            |
| `personal` | path, `cv.json`-relative | `personal.json` |

## Fonts

- Latin: Inter → Adwaita Sans → DejaVu Sans
- CJK: Noto Sans CJK JP
- `.ttc` collections are invisible to `typst fonts`; passed via `--font-path`
- Paths: `$TYPST_FONT_PATH`, `/usr/share/fonts/noto-cjk`, `/usr/share/fonts/opentype/noto`, `/usr/share/fonts/truetype/noto`
- CI: `fonts-noto-cjk` (absent → tofu, exit 0)

## `cv.typ`

| Section order | `employment`, `education`, `freelance`, `opensource`, `project` |
| ------------- | --------------------------------------------------------------- |
| Entry filter  | `onPdf != false`                                                |
| Sort          | `from` descending                                               |
| Nesting       | `parent` → indented engagement rows                             |
| Page breaks   | entries atomic; heading bound to first entry                    |
| Skills        | inline, `·`-joined                                              |

## `rirekisho.typ`

| Block      | Source                                                        |
| ---------- | ------------------------------------------------------------- |
| ふりがな   | `cv.json` → `rirekisho.furigana`                              |
| 氏名       | `cv.json` → `labels.subtitle.ja`                              |
| 生年月日   | `personal.birth`; age computed from `datetime.today()`        |
| 現住所     | `personal.address`                                            |
| 電話       | `personal.phone`                                              |
| メール     | `cv.json` → `contact.email`, `contact.site`                   |
| 学歴       | `education`, ascending, 入学 at `from`, 卒業 at `to`          |
| 職歴       | `employment` minus `praktika`, ascending, 入社 / 退社         |
| 業務委託   | child with no `to` and `from != parent.from`                  |
| 独立       | `freelance-fulltime` milestone                                |
| 月         | `personal.months[<id>].in` / `.out`                           |
| 免許・資格 | `personal.qualifications`, ascending; else `skills.interests` |
| 本人希望   | `personal.note`, prompted by `mise run rirekisho`             |

## `personal.age`

- age passphrase mode, scrypt
- committed; public repo
- passphrase read by `age` from the terminal; never touches a script
- plaintext: 0600, system temp (`personal`) or `pdf/.personal.json` (`rirekisho`), removed in `finally`
- `out/` and `pdf/.personal.json` gitignored; never copied to `dist/`
