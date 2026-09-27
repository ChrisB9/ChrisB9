import { DEFAULT_LOCALE, LOCALES, ui, type Locale } from './ui';

export function isLocale(value: string | undefined): value is Locale {
  return !!value && (LOCALES as readonly string[]).includes(value);
}

export function localeFromUrl(url: URL): Locale {
  const segment = url.pathname.split('/').filter(Boolean)[0];
  return isLocale(segment) ? segment : DEFAULT_LOCALE;
}

export function useTranslations(locale: Locale) {
  return function t(key: keyof (typeof ui)['en'], vars: Record<string, string | number> = {}) {
    const raw = ui[locale][key] ?? ui[DEFAULT_LOCALE][key];
    return raw.replace(/\{(\w+)\}/g, (match, name) => (name in vars ? String(vars[name]) : match));
  };
}

export function localePath(locale: Locale, ...segments: string[]): string {
  const path = segments
    .filter(Boolean)
    .join('/')
    .replace(/^\/+|\/+$/g, '');
  return path ? `/${locale}/${path}` : `/${locale}`;
}

export function alternateLinks(locale: Locale, url: URL) {
  const rest = url.pathname.split('/').filter(Boolean).slice(1).join('/');
  return LOCALES.map((candidate) => ({
    locale: candidate,
    href: localePath(candidate, rest),
    current: candidate === locale,
  }));
}

export function ageFrom(birthYear: number, birthMonth: number, birthDay: number): number {
  const now = new Date();
  let age = now.getFullYear() - birthYear;
  const hadBirthday =
    now.getMonth() + 1 > birthMonth ||
    (now.getMonth() + 1 === birthMonth && now.getDate() >= birthDay);
  if (!hadBirthday) age -= 1;
  return age;
}

export function formatRange(
  patterns: { rangeSince: string; rangeBetween: string; rangeAt: string },
  from: string | null,
  to: string | null,
  open: boolean,
): string {
  if (!from) return '';
  const template = to ? patterns.rangeBetween : open ? patterns.rangeSince : patterns.rangeAt;
  return template.replace('{from}', from).replace('{to}', to ?? '');
}
