

#import "lib.typ": *

#set document(
  title: t(data.labels.title) + " - " + t(data.labels.subtitle),
  author: t(data.labels.subtitle),
)

#set page(
  paper: "a4",
  margin: (top: 16mm, bottom: 16mm, left: 16mm, right: 16mm),
  footer: context {
    set text(size: 7.5pt, fill: ink-mute, font: body-font)
    grid(
      columns: (1fr, auto),
      align(left)[#t(data.labels.subtitle) · #data.contact.email],
      align(right)[#counter(page).display("1 / 1", both: true)],
    )
  },
)

#set text(font: body-font, size: 9.5pt, fill: ink, lang: locale)
#set par(justify: false, leading: 0.68em, spacing: 0.9em)
#show link: set text(fill: accent)

#let section-title(body) = {
  block(above: 16pt, below: 9pt)[
    #set text(size: 8pt, fill: ink-mute, weight: 600, tracking: 0.12em)
    #upper(body)
    #v(3pt, weak: true)
    #line(length: 100%, stroke: 0.5pt + line-color)
  ]
}

#let row(date, body) = block(below: 11pt, breakable: false)[
  #grid(
    columns: (date-width, 1fr),
    column-gutter: 7mm,
    {
      set text(size: 7.5pt, fill: ink-mute, font: mono-font, tracking: 0.04em)
      upper(date)
    },
    body,
  )
]

#let bullets(items) = {
  if items.len() == 0 { return }
  set text(size: 9pt, fill: ink-soft)

  set par(leading: 0.5em)
  v(3pt, weak: true)
  for item in items {
    grid(
      columns: (3.2mm, 1fr),
      column-gutter: 0pt,
      text(fill: ink-mute)[•],
      item,
    )
    v(5pt, weak: true)
  }
}

#let portrait = "portrait.jpg"
#let logo = "../public/static/icon-512x512.png"

#grid(
  columns: (1fr, 26mm),
  column-gutter: 8mm,
  align: (left + top, right + top),
  {
    block(below: 8pt, image(logo, height: 9mm))

    block(below: 12pt, text(size: 19pt, weight: 700)[#t(data.labels.subtitle)])
    block(
      below: 10pt,
      text(size: 9.5pt, fill: ink-soft)[
        #set par(leading: 0.75em)
        #t(data.intro)
      ],
    )
    block(text(size: 8.5pt, fill: ink-mute, font: mono-font)[
      #data.contact.email · #data.contact.site · #t(data.contact.location)
    ])
  },

  block(clip: true, radius: 2mm, image(portrait, width: 26mm)),
)

#v(7pt)

#let order = ("employment", "education", "freelance", "opensource", "project")
#let newest-first(entries) = entries.sorted(key: e => -1 * int(
  if e.from == none { 0 } else { e.from },
))

#for key in order {
  let all = data.entries.filter(e => e.section == key and e.at("onPdf", default: true))
  let parents = newest-first(all.filter(e => e.at("parent", default: none) == none))
  if parents.len() == 0 { continue }

  let title = section-title(t(data.labels.sections.at(key)))
  let first = true

  for entry in parents {
    let kids = newest-first(all.filter(e => e.at("parent", default: none) == entry.id))
    let org = t(entry.at("org", default: none))
    let summary = t(entry.summary)
    let url = entry.at("url", default: none)

    let entry-row = row(
      range(entry.from, entry.to, open: entry.section != "milestone"),
      {
        if org != "" {
          block(below: 6pt, text(weight: 600)[
            #if url != none { link(url)[#org] } else { org }
          ])
        }
        if summary != "" {
          block(below: 3pt, text(size: 9pt, fill: ink-soft)[#summary])
        }
        bullets(tlist(entry.bullets))

        if kids.len() > 0 {
          v(7pt)
          block(
            inset: (left: 3.5mm),
            stroke: (left: 0.5pt + line-color),
            {
              for (i, kid) in kids.enumerate() {
                block(above: if i == 0 { 1pt } else { 7pt }, below: 0pt, grid(
                  columns: (1fr, auto),
                  column-gutter: 4mm,
                  text(size: 9pt, weight: 600)[#t(kid.summary)],
                  text(size: 7.5pt, fill: ink-mute, font: mono-font)[
                    #range(kid.from, kid.to, open: kid.section != "milestone")
                  ],
                ))
                bullets(tlist(kid.bullets))
              }
            },
          )
        }
      },
    )

    if first {
      block(breakable: false, title + entry-row)
      first = false
    } else {
      entry-row
    }
  }

  if key == "project" {
    block(below: 11pt, grid(
      columns: (date-width, 1fr),
      column-gutter: 7mm,
      [],
      text(size: 8.5pt, fill: ink-mute)[#t(data.labels.more)],
    ))
  }
}

#block(breakable: false, section-title(t(data.labels.sections.skills)) + {
  for group in data.skills {
    row(t(group.label), {
      set text(size: 9pt, fill: ink-soft)
      set par(leading: 0.5em)
      tlist(group.items).join(text(fill: ink-mute)[~· ])
    })
  }
})
