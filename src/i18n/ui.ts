export const LOCALES = ['en', 'de', 'ja'] as const;
export type Locale = (typeof LOCALES)[number];
export const DEFAULT_LOCALE: Locale = 'en';

export const localeNames: Record<Locale, string> = {
  en: 'English',
  de: 'Deutsch',
  ja: '日本語',
};

export const localeTags: Record<Locale, string> = {
  en: 'en',
  de: 'de',
  ja: 'ja',
};

type Dict = {
  'nav.about': string;
  'nav.work': string;
  'nav.playground': string;
  'nav.cv': string;
  'nav.menu': string;
  'index.positioning': string;
  'index.map.label': string;
  'index.map.countries': string;
  'index.map.lived': string;
  'index.map.visited': string;
  'index.map.here': string;
  'journey.now': string;
  'journey.move': string;
  'journey.title': string;
  'stack.tools': string;
  'stack.languages': string;
  'stack.others': string;
  'stack.editor': string;
  'stack.ai': string;
  'stack.vcs': string;
  'stack.os': string;
  'stack.pm': string;
  'work.filter': string;
  'work.all': string;
  'work.client': string;
  'work.own': string;
  'work.active': string;
  'work.past': string;
  'work.notPublic': string;
  'work.source': string;
  'playground.intro': string;
  'playground.tictactoe.blurb': string;
  'tictactoe.enjoy': string;
  'tictactoe.turn': string;
  'tictactoe.wins': string;
  'tictactoe.draw': string;
  'tictactoe.reset': string;
  'tictactoe.scores': string;
  'tictactoe.cell': string;
  'footer.madeWith': string;
  'footer.imprint': string;
  'lang.switch': string;
  skip: string;
  'a11y.reading': string;
  untranslated: string;
};

export const ui: Record<Locale, Dict> = {
  en: {
    'nav.about': 'About me',
    'nav.work': 'Work',
    'nav.playground': 'Playground',
    'nav.cv': 'CV',
    'nav.menu': 'Navigation',
    'index.positioning': 'Digital Nomad and Freelancer',
    'index.map.label': 'World map of the countries I have visited',
    'index.map.countries': '{n} countries',
    'index.map.lived': 'lived in',
    'index.map.visited': 'visited',
    'index.map.here': 'currently in {country}',
    'journey.now': 'now',
    'journey.move': 'Moved',
    'journey.title': 'Timeline',
    'stack.tools': 'Tools',
    'stack.languages': 'Languages',
    'stack.others': 'Others',
    'stack.editor': 'Development',
    'stack.ai': 'AI',
    'stack.vcs': 'Version control',
    'stack.os': 'OS',
    'stack.pm': 'Project management',
    'work.filter': 'Filter',
    'work.all': 'Everything',
    'work.client': 'Client work',
    'work.own': 'Own projects',
    'work.active': 'Current',
    'work.past': 'Earlier',
    'work.notPublic': 'not public',
    'work.source': 'source',
    'playground.intro': 'Experiments that run in your browser. More will land here over time.',
    'playground.tictactoe.blurb': 'Two-player tic tac toe, written in plain TypeScript.',
    'tictactoe.enjoy': 'Enjoy playing',
    'tictactoe.turn': "Player {symbol}'s turn",
    'tictactoe.wins': 'Player {symbol} wins!',
    'tictactoe.draw': 'No winner this time.',
    'tictactoe.reset': 'New game',
    'tictactoe.scores': 'Wins',
    'tictactoe.cell': 'Cell {n}',
    'footer.madeWith': 'Made with coffee by',
    'footer.imprint': 'Imprint',
    'lang.switch': 'Change language',
    skip: 'Skip to content',
    'a11y.reading': 'Larger text and higher contrast',
    untranslated: 'This page has not been translated yet. Showing the English version.',
  },
  de: {
    'nav.about': 'Über mich',
    'nav.work': 'Arbeit',
    'nav.playground': 'Spielwiese',
    'nav.cv': 'Lebenslauf',
    'nav.menu': 'Navigation',
    'index.positioning': 'Digitaler Nomade und Freelancer',
    'index.map.label': 'Weltkarte der Länder, die ich besucht habe',
    'index.map.countries': '{n} Länder',
    'index.map.lived': 'gelebt',
    'index.map.visited': 'besucht',
    'index.map.here': 'zurzeit in {country}',
    'journey.now': 'heute',
    'journey.move': 'Umzug',
    'journey.title': 'Timeline',
    'stack.tools': 'Werkzeuge',
    'stack.languages': 'Sprachen',
    'stack.others': 'Sonstiges',
    'stack.editor': 'Entwicklung',
    'stack.ai': 'KI',
    'stack.vcs': 'Versionsverwaltung',
    'stack.os': 'OS',
    'stack.pm': 'Projekt-Management',
    'work.filter': 'Filter',
    'work.all': 'Alles',
    'work.client': 'Kundenprojekte',
    'work.own': 'Eigene Projekte',
    'work.active': 'Aktuell',
    'work.past': 'Früher',
    'work.notPublic': 'nicht öffentlich',
    'work.source': 'Quellcode',
    'playground.intro': 'Experimente, die im Browser laufen. Mit der Zeit kommt mehr dazu.',
    'playground.tictactoe.blurb': 'Tic Tac Toe für zwei, geschrieben in reinem TypeScript.',
    'tictactoe.enjoy': 'Viel Spaß',
    'tictactoe.turn': 'Spieler {symbol} ist dran',
    'tictactoe.wins': 'Spieler {symbol} gewinnt!',
    'tictactoe.draw': 'Diesmal gibt es keinen Gewinner.',
    'tictactoe.reset': 'Neues Spiel',
    'tictactoe.scores': 'Siege',
    'tictactoe.cell': 'Feld {n}',
    'footer.madeWith': 'Gemacht mit Kaffee von',
    'footer.imprint': 'Impressum',
    'lang.switch': 'Sprache wechseln',
    skip: 'Zum Inhalt springen',
    'a11y.reading': 'Größere Schrift und mehr Kontrast',
    untranslated: 'Diese Seite ist noch nicht übersetzt. Angezeigt wird die englische Fassung.',
  },
  ja: {
    'nav.about': '自己紹介',
    'nav.work': '仕事',
    'nav.playground': 'プレイグラウンド',
    'nav.cv': '履歴書',
    'nav.menu': 'ナビゲーション',
    'index.positioning': 'デジタルノマド兼フリーランス',
    'index.map.label': '訪れた国の世界地図',
    'index.map.countries': '{n} か国',
    'index.map.lived': '住んだ国',
    'index.map.visited': '訪れた国',
    'index.map.here': '現在は{country}',
    'journey.now': '現在',
    'journey.move': '引っ越し',
    'journey.title': 'タイムライン',
    'stack.tools': 'ツール',
    'stack.languages': '言語',
    'stack.others': 'その他',
    'stack.editor': '開発',
    'stack.ai': 'AI',
    'stack.vcs': 'バージョン管理',
    'stack.os': 'OS',
    'stack.pm': 'プロジェクト管理',
    'work.filter': '絞り込み',
    'work.all': 'すべて',
    'work.client': 'クライアントワーク',
    'work.own': '自分のプロジェクト',
    'work.active': '現在',
    'work.past': 'これまで',
    'work.notPublic': '非公開',
    'work.source': 'ソース',
    'playground.intro': 'ブラウザで動く実験場です。今後も少しずつ増やしていきます。',
    'playground.tictactoe.blurb': '素の TypeScript で書いた二人用の三目並べ。',
    'tictactoe.enjoy': 'お楽しみください',
    'tictactoe.turn': 'プレイヤー {symbol} の番です',
    'tictactoe.wins': 'プレイヤー {symbol} の勝ちです！',
    'tictactoe.draw': '今回は引き分けです。',
    'tictactoe.reset': '新しいゲーム',
    'tictactoe.scores': '勝利数',
    'tictactoe.cell': 'マス {n}',
    'footer.madeWith': 'コーヒーとともに作成',
    'footer.imprint': '運営者情報',
    'lang.switch': '言語を変更',
    skip: '本文へスキップ',
    'a11y.reading': '文字を大きく、コントラストを強く',
    untranslated: 'このページはまだ翻訳されていません。英語版を表示しています。',
  },
};
