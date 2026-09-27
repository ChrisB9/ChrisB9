import type { Locale } from '../i18n/ui';
import { DEFAULT_LOCALE } from '../i18n/ui';
import cv from '../data/cv.json';

export type Translated<T = string> = Partial<Record<Locale, T>>;

export function t<T>(value: Translated<T> | null | undefined, locale: Locale, empty: T): T {
  if (!value) return empty;
  const own = value[locale];
  if (own !== undefined && own !== null && (!Array.isArray(own) || own.length > 0) && own !== '') {
    return own;
  }
  return value[DEFAULT_LOCALE] ?? empty;
}

export const text = (value: Translated | null | undefined, locale: Locale) => t(value, locale, '');

export const list = (value: Translated<string[]> | null | undefined, locale: Locale) =>
  t(value, locale, [] as string[]);

export const contact = cv.contact;
export const labels = cv.labels;
export const skills = cv.skills;
export const intro = cv.intro;
