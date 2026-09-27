

#import "lib.typ": data, t, tlist

#let name = data.labels.subtitle.ja
#let reading = data.rirekisho.furigana

#let personal = json(sys.inputs.at("personal", default: "personal.json"))
#let p(key, default: none) = personal.at(key, default: default)

#set document(title: "履歴書 " + name, author: name)
#set page(paper: "a4", margin: (x: 14mm, y: 13mm))
#set text(font: ("Noto Sans CJK JP", "Adwaita Sans"), size: 9pt, lang: "ja")
#set par(leading: 0.6em)

#let rule = 0.5pt + rgb("#333333")
#let label-cell(body) = table.cell(fill: rgb("#f2f2f2"), align: horizon + center, text(size: 7.5pt)[#body])
#let blank(body) = text(fill: rgb("#999999"), body)

#grid(
  columns: (1fr, auto),
  align: (bottom, bottom),
  text(size: 17pt, weight: 700, tracking: 0.3em)[履歴書],
  text(size: 8.5pt)[#blank[＿＿＿＿]年 #blank[＿＿]月 #blank[＿＿]日現在],
)

#v(5pt)

#grid(
  columns: (1fr, 30mm),
  column-gutter: 4mm,
  table(
    columns: (18mm, 1fr),
    rows: (9mm, 18mm, 12.4mm),
    stroke: rule,
    inset: (x: 5pt, y: 6pt),
    label-cell[ふりがな], table.cell(align: horizon, text(size: 8.5pt)[#reading]),
    label-cell[氏　　名], table.cell(align: horizon, text(size: 13pt)[#name]),
    label-cell[生年月日],
    table.cell(align: horizon)[
      #let b = p("birth")
      #if b != none and b.year != none [
        #let today = datetime.today()
        #let had-birthday = (today.month(), today.day()) >= (b.month, b.day)
        #let years = today.year() - b.year - if had-birthday { 0 } else { 1 }
        #b.year 年 #b.month 月 #b.day 日生　（満 #years 歳）
      ] else [
        #blank[＿＿＿＿]年 #blank[＿＿]月 #blank[＿＿]日生　（満 #blank[＿＿] 歳）
      ]
    ],
  ),

  block(
    width: 30mm,
    height: 40mm,
    stroke: rule,
    align(center + horizon, text(size: 7.5pt, fill: rgb("#999999"))[写真\ 縦40mm\ 横30mm]),
  ),
)

#v(-2.5pt)

#table(
  columns: (18mm, 1fr, 18mm, 42mm),
  stroke: rule,
  inset: (x: 5pt, y: 6pt),
  label-cell[現 住 所],
  table.cell(align: horizon)[
    #let a = p("address")
    #if a != none and a.text != "" [
      #if a.furigana != "" [#text(size: 7.5pt)[#a.furigana]\ ]
      〒#a.postal　#a.text
    ] else [〒#blank[＿＿＿－＿＿＿＿]]
  ],
  label-cell[電　話],
  table.cell(align: horizon)[
    #let tel = p("phone", default: "")
    #if tel != "" [#tel] else [#blank[＿＿＿＿＿＿]]
  ],
  label-cell[メール],
  table.cell(colspan: 3, align: horizon)[#data.contact.email　・　#data.contact.site],
)

#let entries-of(section) = data.entries.filter(e => e.section == section)
#let oldest-first(list) = list.sorted(key: e => int(if e.from == none { 0 } else { e.from }))
#let org-of(e) = t(e.at("org", default: none))

#let months = p("months", default: (:))

#let month-of(id, kind) = {
  let m = months.at(id, default: (:))
  if type(m) != dictionary { return none }
  m.at(kind, default: none)
}

#let rows = ()

#rows.push((year: none, month: none, body: align(center)[学歴]))
#for e in oldest-first(entries-of("education")) {
  rows.push((year: e.from, month: month-of(e.id, "in"), body: org-of(e) + "　入学"))
  if e.to != none {
    rows.push((year: e.to, month: month-of(e.id, "out"), body: org-of(e) + "　卒業"))
  }
}

#rows.push((year: none, month: none, body: []))
#rows.push((year: none, month: none, body: align(center)[職歴]))

#let employment = oldest-first(
  entries-of("employment").filter(e => e.at("parent", default: none) == none and e.id != "praktika"),
)
#let ongoing = false

#for e in employment {
  let org = org-of(e)
  rows.push((year: e.from, month: month-of(e.id, "in"), body: org + "　入社"))
  let kids = oldest-first(data.entries.filter(k => k.at("parent", default: none) == e.id))
  if kids.len() > 0 {
    for k in kids {
      if k.to != none {
        rows.push((year: k.to, month: month-of(k.id, "out"), body: "同社　退社"))
      } else if k.from != e.from {
        rows.push((year: k.from, month: month-of(k.id, "in"), body: "同社と業務委託契約を締結"))
        ongoing = true
      }
    }
  } else if e.to != none {
    rows.push((year: e.to, month: month-of(e.id, "out"), body: "同社　退社"))
  } else {
    ongoing = true
  }
}

#let fulltime = data.entries.find(e => e.id == "freelance-fulltime")
#if fulltime != none {
  rows.push((year: fulltime.from, month: month-of(fulltime.id, "in"), body: "フリーランスとして独立"))
  ongoing = true
}

#if ongoing { rows.push((year: none, month: none, body: "現在に至る")) }
#rows.push((year: none, month: none, body: align(right)[以上]))

#v(4pt)

#table(
  columns: (16mm, 12mm, 1fr),
  stroke: rule,
  inset: (x: 5pt, y: 5.5pt),
  align: horizon,
  label-cell[年], label-cell[月], label-cell[学歴・職歴],
  ..rows
    .map(r => (
      align(center, if r.year == none { [] } else { text(size: 8.5pt)[#r.year] }),
      align(center, if r.year == none {
        []
      } else if r.month == none {
        blank[＿＿]
      } else {
        text(size: 8.5pt)[#r.month]
      }),
      r.body,
    ))
    .flatten(),
)

#let interests = data.skills.find(g => g.key == "interests")
#let languages = data.skills.find(g => g.key == "languages")

#v(4pt)

#table(
  columns: (16mm, 12mm, 1fr),
  stroke: rule,
  inset: (x: 5pt, y: 5.5pt),
  align: horizon,
  label-cell[年], label-cell[月], label-cell[免許・資格],

  ..p("qualifications", default: tlist(interests.items).map(i => (year: none, month: none, text: i)))

    .sorted(key: q => if q.year == none {
      999999
    } else {
      q.year * 100 + q.at("month", default: 0)
    })
    .map(q => (
      align(center, if q.year == none { blank[＿＿＿] } else { text(size: 8.5pt)[#q.year] }),
      align(center, if q.at("month", default: none) == none { blank[＿＿] } else { text(size: 8.5pt)[#q.month] }),
      [#q.text],
    ))
    .flatten(),
)

#v(4pt)

#table(
  columns: (1fr),
  stroke: rule,
  inset: (x: 6pt, y: 6pt),
  label-cell[特技・語学・アピールポイント],
  table.cell(inset: (x: 6pt, y: 7pt))[
    #t(data.intro)

    語学: #tlist(languages.items).join("　・　")
  ],
)

#v(4pt)

#table(
  columns: (1fr),
  stroke: rule,
  inset: (x: 6pt, y: 6pt),
  label-cell[本人希望記入欄],
  table.cell(inset: (x: 6pt, y: 7pt))[
    #let note = p("note", default: "")
    #if note != "" [#note] else [#v(20mm)]
  ],
)
