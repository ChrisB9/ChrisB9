import { site } from '../data/site';

const reverse = (value: string) => [...value].reverse().join('');

const [user = '', domain = ''] = site.email.split('@');

export const emailAttrs = {
  'data-email-u': reverse(user),
  'data-email-d': reverse(domain),
};

export const emailReadable = `${user} (at) ${domain.replaceAll('.', ' (punkt) ')}`;

export const emailPlaceholder = [
  `<span class="email" data-email-u="${reverse(user)}" data-email-d="${reverse(domain)}">`,
  `<noscript>${emailReadable}</noscript>`,
  '</span>',
].join('');
