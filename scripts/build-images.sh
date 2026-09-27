#!/usr/bin/env bash

set -euo pipefail

cd "$(dirname "$0")/.."

SRC="src/assets/images/frame-1.png"
OUT="public/static/share.jpg"
TMP="$(mktemp -d)"
trap 'rm -rf "$TMP"' EXIT

CANVAS="#110f1a"
INK="#f0ecff"
MUTED="#8a84a6"
ACCENT="#a855f7"

PANEL_W=560
PANEL_H=630
FADE_W=200

FONT="$(fc-match -f '%{file}' 'Adwaita Sans' 2>/dev/null || true)"
[ -f "${FONT:-}" ] || FONT="DejaVu-Sans"

magick "$SRC" -resize 864x1536! -crop "${PANEL_W}x${PANEL_H}+100+417" +repage "$TMP/photo.png"

magick -size "${PANEL_W}x${PANEL_H}" xc:white \
  \( -size "${PANEL_H}x${FADE_W}" gradient:black-white -rotate -90 +repage \) \
  -geometry +0+0 -composite "$TMP/mask.png"

magick "$TMP/photo.png" "$TMP/mask.png" \
  -alpha off -compose CopyOpacity -composite "$TMP/photo-faded.png"

magick -size 1200x630 "xc:$CANVAS" \
  "$TMP/photo-faded.png" -geometry "+$((1200 - PANEL_W))+0" -compose over -composite \
  -font "$FONT" \
  -fill "$MUTED" -pointsize 26 -annotate +72+250 "cben.dev" \
  -fill "$INK" -pointsize 58 -annotate +72+330 "Christian Rodriguez" \
  -fill "$INK" -pointsize 58 -annotate +72+398 "Benthake" \
  -fill "$ACCENT" -pointsize 27 -annotate +72+462 "Senior Software Engineer · Freelancer" \
  -fill "$MUTED" -pointsize 27 -annotate +72+500 "Digital Nomad" \
  -quality 88 "$OUT"

echo "wrote $OUT ($(du -h "$OUT" | cut -f1))"

CV_OUT="pdf/portrait.jpg"
magick "$SRC" -resize 864x1536! -crop 512x630+124+417 +repage -quality 88 "$CV_OUT"
echo "wrote $CV_OUT ($(du -h "$CV_OUT" | cut -f1))"
