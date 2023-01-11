<?php

declare(strict_types=1);

use App\Repository\PageRepository;
use App\Repository\ProjectRepository;

return [
    '' => [
        'id' => 0,
        'title' => 'index',
        'file' => 'index.html.twig',
        'contentType' => PageRepository::CONTENT_TYPE_TWIG,
        'sitemap' => [
            'priority' => 1
        ],
        'seo' => [],
    ],
    'about' => [
        'id' => 1,
        'title' => 'about',
        'file' => 'about',
        'contentType' => PageRepository::CONTENT_TYPE_MARKDOWN,
        'seo' => [],
    ],
    'freelancing' => [
        'id' => 2,
        'title' => 'freelancing',
        'file' => 'freelancing',
        'contentType' => PageRepository::CONTENT_TYPE_MARKDOWN,
        'activeClients' => fn() => ProjectRepository::loadActiveClient(),
        'inactiveClients' => fn() => ProjectRepository::loadInactiveClient(),
        'seo' => [],
    ],
    'imprint' => [
        'id' => 3,
        'title' => 'imprint',
        'file' => 'imprint',
        'contentType' => PageRepository::CONTENT_TYPE_MARKDOWN,
        'sitemap' => [],
        'seo' => [],
    ],
    'readinglist' => [
        'id' => 4,
        'title' => 'readinglist',
        'file' => 'readinglist',
        'contentType' => PageRepository::CONTENT_TYPE_MARKDOWN,
        'sitemap' => [
            'enabled' => false
        ],
        'seo' => [],
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
        'seo' => [],
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
                    'year' => '2020-2021',
                    'title' => 'Geniocon Online Marketing',
                    'notes' => [
                        'Consulting, Support, Upgrades und Weiterentwicklung für die Website-Instanzen basierend auf WordPress',
                    ],
                ],
                [
                    'year' => 'seit 2019',
                    'title' => 'Trainingsinsel GmbH / Sitzkrieger',
                    'notes' => [
                        'Seit 2022: Entwicklung einer Trainer-Software zum Testen und Auswerten der Fitness von Mitarbeitenden in Unternehmen',
                        '<a href="https://sitzkrieger.com">Sitzkrieger</a>: Konzeption, Umsetzung und Hosting der Sitzkrieger.com Website basierend auf PimCore, Symfony und ReactJS',
                        'Support und Weiterentwicklung der Trainingsinsel-WordPress-Instanzen',
                    ],
                ],
                [
                    'year' => 'seit 2018',
                    'title' => 'SanoGym GbR',
                    'notes' => [
                        'Bis 2021: Relaunch der Sanogym.com-Website (WordPress mit Gutenberg und Custom Sage-Theme, ReactJS, Elementor)',
                        'Seit 2022: Weiterentwicklung einzelner Blöcke für Gutenberg und Elementor auf der Website - Integration mit MindBody'
                    ],
                ],
                [
                    'year' => '2016-2022',
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
                    'year' => 'seit 2021',
                    'title' => <<<HTML
eCentral GmbH <br />
Senior Software-Entwickler
HTML,
                    'notes' => [
                        'Entwerfen der Architektur sowie Implementierung der Integration von DAM-Anbindungen an u.a. TYPO3 als FAL-Driver (siehe: <a href="https://packagist.org/packages/fairway/">Fairway auf packagist</a>)',
                        'Bereitstellen von Entwicklungsumgebungen mit automatisiertem SSL-Zertifikat und Server Infrastruktur',
                        '(Neu-/Weiter-)Entwicklungen von/an Webseiten sowie Schnittstellen mit TYPO3, PimCore, Symfony und WordPress',
                        'Frontend-Entwicklung mit u.a. React bzw. Vue/Typescript und Cypress',
                        'Anleitung von Kollegen besonders im Bereich PHP und TYPO3'
                    ]
                ],
                [
                    'year' => '2017-2021',
                    'title' => <<<HTML
anders und sehr GmbH <br />
(Backend-) Web-Entwickler
HTML,
                    'notes' => [
                        'Konzipieren und Entwickeln von Schnittstellen sowie Im- und Exporten',
                        'Erstellen von Entwicklungsumgebungen mit Docker',
                        'Aufbau/Anbinden von Suchindizes an diverse CMS',
                        '(Neu-)Entwicklungen von Webseiten mit TYPO3 und PimCore',
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
                    'year' => 'rust_dockerfile_generator',
                    'title' => <<<HTML
Dockerfile Generator mit handlebar templates<br />
<a class="text-blue-600" href="https://github.com/ChrisB9/rust_dockerfile_generator">https://github.com/ChrisB9/rust_dockerfile_generator</a>
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
            'Closed-Source Projekte' => [
                [
                    'year' => 'delta mental math',
                    'title' => 'Flutter App zum Kopfrechnen: Bestimmung von Formelzeichen (z.B 10 ? 3 ? 1 = 13)',
                    'notes' => [
                        'Die Flutter-App basiert auf dieser Idee: <a href="https://github.com/ChrisB9/delta-test-mental-math">JS: delta-test-mental</a>',
                        'Programmiert in Flutter / Dart mit vollkommen randomisierten Formeln und verschiedenem Schwierigkeitsgrad'
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
                        'Japanisch - Anfänger A1 VHS Kurs',
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
                    'year' => 'Tech-Stack',
                    'title' => 'Programmiersprachen und Werkzeuge<br/>',
                    'notes' => [
                        'Sprachen+: PHP, JavaScript, Rust, Dart, mysql/mssql, Shell, HTML/CSS, Flutter, Dockerfile, C',
                        'Tools: docker, linux (pc+hosting), GitHub/GitLab/Space, JetBrains IDEs',
                        'Fürs Freelancing: JetBrains Space, GitLab, Hetzner, invoiz',
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
        'title' => 'contact',
        'file' => 'contact',
        'contentType' => PageRepository::CONTENT_TYPE_MARKDOWN,
        'seo' => [],
    ],
    'projects' => [
        'id' => 7,
        'title' => 'projects',
        'file' => 'projects',
        'contentType' => PageRepository::CONTENT_TYPE_MARKDOWN,
        'activeProjects' => fn() => ProjectRepository::loadActive(),
        'inactiveProjects' => fn() => ProjectRepository::loadInactive(),
        'seo' => [],
    ],
];
