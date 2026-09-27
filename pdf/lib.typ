

#let locale = sys.inputs.at("locale", default: "en")
#let data = json("../src/data/cv.json")

#let t(value) = {
  if value == none { return "" }
  value.at(locale, default: value.at("en", default: ""))
}

#let tlist(value) = {
  if value == none { return () }
  value.at(locale, default: value.at("en", default: ()))
}

#let range(from, to, open: true) = {
  if from == none { return "" }
  let pattern = if to != none {
    t(data.labels.rangeBetween)
  } else if open {
    t(data.labels.rangeSince)
  } else {
    t(data.labels.rangeAt)
  }
  pattern.replace("{from}", str(from)).replace("{to}", if to == none { "" } else { str(to) })
}

#let body-font = ("Inter", "Adwaita Sans", "DejaVu Sans", "Noto Sans CJK JP")
#let mono-font = ("Adwaita Mono", "DejaVu Sans Mono", "Noto Sans CJK JP")

#let ink = rgb("#0f172a")
#let ink-soft = rgb("#334155")
#let ink-mute = rgb("#64748b")
#let accent = rgb("#1b2a5e")
#let line-color = rgb("#cbd5e1")

#let date-width = if locale == "ja" { 30mm } else { 26mm }
