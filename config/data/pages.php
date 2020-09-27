<?php

declare(strict_types=1);

use App\Repository\PageRepository;
use App\Repository\ProjectRepository;

return [
    '' => [
        'id' => 0,
        'title' => 'Christian Rodriguez Benthake - Developer and Freelancer',
        'file' => 'index.html.twig',
        'contentType' => PageRepository::CONTENT_TYPE_TWIG,
        'sitemap' => [
            'priority' => 1
        ],
        'seo' => [
            'description' => 'Software engineer and web developer for primarly modern PHP, and JavaScript + Freelancer in Kiel and Stuttgart',
        ],
    ],
    'about' => [
        'id' => 1,
        'title' => 'About Me',
        'file' => 'about',
        'contentType' => PageRepository::CONTENT_TYPE_MARKDOWN,
        'seo' => [
            'description' => 'I am a full-stack webdeveloper and freelancer, working primarily with Docker, PHP, WordPress, PimCore, TYPO3, Vue, and ReactJS',
        ],
    ],
    'freelancing' => [
        'id' => 2,
        'title' => 'Freelancing',
        'file' => 'freelancing',
        'contentType' => PageRepository::CONTENT_TYPE_MARKDOWN,
        'activeClients' => fn() => ProjectRepository::loadActiveClient(),
        'inactiveClients' => fn() => ProjectRepository::loadInactiveClient(),
        'seo' => [
            'description' => 'References as a freelancing web developer',
        ],
    ],
    'imprint' => [
        'id' => 3,
        'title' => 'Imprint',
        'file' => 'imprint',
        'contentType' => PageRepository::CONTENT_TYPE_MARKDOWN,
        'seo' => [
            'description' => 'Impressum',
            'robots' => 'noindex, nofollow',
        ],
    ],
    'readinglist' => [
        'id' => 4,
        'title' => 'Readinglist',
        'file' => 'readinglist',
        'contentType' => PageRepository::CONTENT_TYPE_MARKDOWN,
        'sitemap' => [
            'enabled' => false
        ],
        'seo' => [
            'description' => 'Relevant Links and Resources for topics such as climate change and racism',
        ],
    ],
    'projects/tictactoe' => [
        'id' => 5,
        'title' => 'Tic Tac Toe',
        'file' => 'tictactoe',
        'contentType' => PageRepository::CONTENT_TYPE_MARKDOWN,
        'metadata' => [
            'enableTicTacToe' => true,
        ],
        'sitemap' => [
            'enabled' => false,
        ],
        'seo' => [
            'robots' => 'noindex, nofollow',
            'description' => 'Play a round of tictactoe',
        ],
    ],
    'projects/timestamp-generator' => [
        'id' => 10,
        'title' => 'Timestamp Generator',
        'file' => 'timestamp',
        'contentType' => PageRepository::CONTENT_TYPE_MARKDOWN,
        'sitemap' => [
            'enabled' => true,
        ],
        'seo' => [
            'description' => 'Generate multiple random timestamps between two dates - useful for mock-data',
        ],
    ],
    'projects/collatz-conjecture' => [
        'id' => 16,
        'title' => 'Collatz Conjecture',
        'file' => 'collatzconjecture',
        'contentType' => PageRepository::CONTENT_TYPE_MARKDOWN,
        'sitemap' => [
            'enabled' => false,
        ],
        'seo' => [
            'robots' => 'noindex, nofollow',
            'description' => 'Experimenting with the math problem collatz conjecture and chart.js',
        ],
    ],
    'cv' => [
        'id' => 17,
        'title' => 'Lebenslauf',
        'file' => 'cv',
        'contentType' => PageRepository::CONTENT_TYPE_MARKDOWN,
        'sitemap' => [
            'enabled' => false,
        ],
        'curriculum' => [
            'Freelancing' => [
                [
                    'year' => 'seit 2020',
                    'title' => 'Geniocon Online Marketing',
                    'notes' => [
                        'Consulting, Support, Upgrades und Weiterentwicklung für die Website-Instanzen basierend auf WordPress',
                    ],
                ],
                [
                    'year' => 'seit 2019',
                    'title' => 'Trainingsinsel GmbH',
                    'notes' => [
                        'Support und Weiterentwicklung der Trainingsinsel.de-WordPress-Instanz',
                        'Konzeption, Umsetzung und Hosting der Sitzkrieger.com Website basierend auf PimCore, Symfony und ReactJS',
                    ],
                ],
                [
                    'year' => 'seit 2018',
                    'title' => 'SanoGym GbR',
                    'notes' => [
                        'Relaunch der Sanogym.com-Website (WordPress mit Gutenberg und Custom Sage-Theme, ReactJS, Elementor)',
                    ],
                ],
                [
                    'year' => 'seit 2016',
                    'title' => 'Stiftung managerohnegrenzen gGmbH',
                    'notes' => [
                        '2016-2017 Relaunch der Website mit WordPress',
                        'Seit 2017 Support und Weiterentwicklung der Website',
                        'Digitalisierung interner Prozesse',
                    ],
                ],

                [
                    'year' => '2015-2018',
                    'title' => 'Lauer-Repair GmbH',
                    'notes' => [
                        'Entwicklung eines Plugins zur Verwaltung von Rechnungen (+PDF), Produkten und Kunden innerhalb der Website',
                    ],
                ],
            ],
            'Berufliche Laufbahn' => [
                [
                    'year' => 'seit 2017',
                    'title' => <<<HTML
anders und sehr GmbH <br />
(Backend-) Web-Entwickler
HTML,
                    'notes' => [
                        'Konzipieren und Entwickeln von Schnittstellen sowie Im- und Exporten',
                        'Erstellen von Entwicklungsumgebungen mit Docker',
                        'Aufbau/Anbinden von Suchindexen an diverse CMS',
                        '(Neu-)Entwicklungen von Websiten mit TYPO3 und PimCore',
                        'Performance-Steigerung und QA von Webanwendungen',
                        'Umsetzen von Frontend-Anwendungen mit u.a. Vue/Typescript'
                    ]
                ],
                [
                    'year' => '2014-2017',
                    'title' => <<<HTML
audius AG<br />
Duales Studium, Software-Entwicklung
HTML,
                    'notes' => [
                        'Erlernen von C# und MSSQL',
                        'Weiterentwicklung des Produktes Dashface',
                        'Kosten-Nutzen-Analyse von div. Mapping-Systemen für Dashface'
                    ],
                ],
                [
                    'year' => '2012-2013',
                    'title' => 'Kurzpraktika in versch. Software-Entwicklungs-Unternehmen',
                    'notes' => [
                        'Mehrtägiges Praktikum bei OrgaData in der Software-Entwicklung mit C#',
                        'Eintägiges Praktikum bei Tridem Leer im Bereich WebDesign',
                        'Eintägiges Praktikum bei BussData Leer im Bereich Software-Entwicklung',
                        'Eintägiges Praktikum bei ConneData Leer im Bereich Software-Entwicklung',
                    ],
                ],
            ],
            'Schullaufbahn' => [
                [
                    'year' => '2014-2017',
                    'title' => <<<HTML
Duale Hochschule Baden-Württemberg<br />
Angewandte Informatik<br />
Abschluss: Bachelor of Science
HTML,
                ],
                [
                    'year' => '2006-2014',
                    'title' => <<<HTML
Gymnasium Rhauderfehn <br />
Abschluss: Abitur
HTML,
                ],
            ],
            'Open-Source Projekte' => [
                [
                    'year' => 'phpsu',
                    'title' => <<<HTML
Entwicklung eines Werkzeugs zur Synchronisation von Dateien und Datenbanken zwischen verschiedenen Umgebungen Website <br />
<a class="text-blue-600" href="https://phpsu.de">https://phpsu.de</a>
HTML,
                ],
                [
                    'year' => 'Shell Command Builder',
                    'title' => <<<HTML
Bibliothek um (komplexere) Shell-Befehle in PHP im OOP-Stil zu generieren<br />
<a class="text-blue-600" href="https://github.com/phpsu/ShellCommandBuilder">https://github.com/phpsu/ShellCommandBuilder</a>
HTML,
                ],
                [
                    'year' => 'docker images',
                    'title' => 'Erstellen verschiedener Docker-Images',
                    'notes' => [
                        'pimcore-dev: <a class="text-blue-600" href="https://github.com/ChrisB9/pimcore-docker">https://github.com/ChrisB9/pimcore-docker</a>',
                        'php8: <a class="text-blue-600" href="https://github.com/ChrisB9/php8-xdebug">https://github.com/ChrisB9/php8-xdebug</a>',
                    ],
                ],
            ],
            'Kenntnisse und Interessen' => [
                [
                    'year' => 'Sprachen',
                    'title' => 'Sprachkenntnisse',
                    'notes' => [
                        'Deutsch - Muttersprache',
                        'Englisch - Fließend',
                        'Französisch - Grundkenntnisse',
                    ],
                ],
                [
                    'year' => 'Fliegen',
                    'title' => 'Gleitschirmfliegen',
                    'notes' => [
                        'Lizensiert mit Luftfahrerschein B-Lizenz',
                    ],
                ],
                [
                    'year' => 'Code / Tools',
                    'title' => 'Programmiersprachen und Werkzeuge<br/>Aktuell meistbenutze Sprachen/Tools',
                    'notes' => [
                        'Sprachen: PHP, JavaScript, mysql/mssql, Shell, HTML/CSS, Dockerfile',
                        'Tools: docker, linux (pc+hosting), GitHub/GitLab/Bitbucket, JetBrains IDEs',
                        'Fürs Freelancing: clockify, teamwork.com, GitLab, phabricator, invoiz',
                    ],
                ],
            ],
        ],
        'seo' => [
            'robots' => 'noindex, nofollow',
            'description' => 'Curriculum Vitae - Lebenslauf',
        ],
    ],
    'contact' => [
        'id' => 6,
        'title' => 'Contact me',
        'file' => 'contact',
        'contentType' => PageRepository::CONTENT_TYPE_MARKDOWN,
        'seo' => [
            'description' => 'Contact me via E-Mail or connect with on GitHub, Twitter, Xing, or LinkedIn',
        ],
    ],
    'projects' => [
        'id' => 7,
        'title' => 'Projects',
        'file' => 'projects',
        'contentType' => PageRepository::CONTENT_TYPE_MARKDOWN,
        'activeProjects' => fn() => ProjectRepository::loadActive(),
        'inactiveProjects' => fn() => ProjectRepository::loadInactive(),
        'seo' => [
            'description' => 'Projects I have worked on or am actively working on',
        ],
    ],
];
