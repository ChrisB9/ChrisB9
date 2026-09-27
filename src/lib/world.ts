import { feature } from 'topojson-client';
import { geoNaturalEarth1, geoPath } from 'd3-geo';
import type { FeatureCollection, Geometry } from 'geojson';
import topo from 'world-atlas/countries-110m.json';

export const MAP_WIDTH = 1000;
export const MAP_HEIGHT = 420;

const NAME_ALIASES: Record<string, string> = {
  'United States of America': 'US',
  'Dem. Rep. Congo': 'CD',
  'Dominican Rep.': 'DO',
  'Falkland Is.': 'FK',
  'Fr. S. Antarctic Lands': 'TF',
  "Côte d'Ivoire": 'CI',
  'Central African Rep.': 'CF',
  Congo: 'CG',
  'Eq. Guinea': 'GQ',
  eSwatini: 'SZ',
  'W. Sahara': 'EH',
  Palestine: 'PS',
  Myanmar: 'MM',
  Turkey: 'TR',
  'Solomon Is.': 'SB',
  'Bosnia and Herz.': 'BA',
  Macedonia: 'MK',
  'Trinidad and Tobago': 'TT',
  'S. Sudan': 'SS',
};

const MICRO_STATES: Record<string, [number, number]> = {
  AD: [42.51, 1.52],
  HK: [22.32, 114.17],
  LI: [47.14, 9.55],
  MC: [43.73, 7.42],
  MT: [35.9, 14.51],
  SG: [1.35, 103.82],
  SM: [43.94, 12.45],
  VA: [41.9, 12.45],
};

function alpha2ByEnglishName(): Map<string, string> {
  const display = new Intl.DisplayNames('en', { type: 'region' });
  const map = new Map<string, string>();
  for (let a = 65; a <= 90; a++) {
    for (let b = 65; b <= 90; b++) {
      const code = String.fromCharCode(a) + String.fromCharCode(b);
      const name = display.of(code);
      if (!name || name === code) continue;
      if (Intl.getCanonicalLocales(`und-${code}`)[0] !== `und-${code}`) continue;
      map.set(name, code);
    }
  }
  return map;
}

function worldFeatures(): FeatureCollection<Geometry, { name: string }> {
  const collection = feature(
    topo as never,
    (topo as never as { objects: { countries: unknown } }).objects.countries as never,
  ) as unknown as FeatureCollection<Geometry, { name: string }>;

  return {
    type: 'FeatureCollection',
    features: collection.features.filter((f) => f.properties?.name !== 'Antarctica'),
  };
}

function mapProjection() {
  return geoNaturalEarth1().fitSize([MAP_WIDTH, MAP_HEIGHT], worldFeatures());
}

export interface CountryPath {
  code: string | null;
  d: string;
}

export function countryPaths(): CountryPath[] {
  const byName = alpha2ByEnglishName();
  const toPath = geoPath(mapProjection());

  return worldFeatures().features.map((f) => {
    const name = f.properties?.name ?? '';
    return {
      code: NAME_ALIASES[name] ?? byName.get(name) ?? null,
      d: (toPath(f) ?? '').replace(/-?\d+(\.\d+)?/g, (n) => String(Math.round(parseFloat(n)))),
    };
  });
}

export interface MicroMarker {
  code: string;
  x: number;
  y: number;
}

export function microMarkers(codes: string[]): MicroMarker[] {
  const drawable = new Set(countryPaths().map((p) => p.code));
  const projection = mapProjection();
  const markers: MicroMarker[] = [];

  for (const code of codes) {
    if (drawable.has(code)) continue;
    const position = MICRO_STATES[code];
    if (!position) continue;
    const point = projection([position[1], position[0]]);
    if (point) markers.push({ code, x: Math.round(point[0]), y: Math.round(point[1]) });
  }
  return markers;
}

export function unmappable(codes: string[]): string[] {
  const drawable = new Set(countryPaths().map((p) => p.code));
  return codes.filter((c) => !drawable.has(c) && !(c in MICRO_STATES));
}

export function projectPoint(coords: [number, number]): { x: number; y: number } | null {
  const point = mapProjection()([coords[1], coords[0]]);
  return point ? { x: point[0], y: point[1] } : null;
}

export function countryName(code: string, locale: string): string {
  return new Intl.DisplayNames(locale, { type: 'region' }).of(code) ?? code;
}
