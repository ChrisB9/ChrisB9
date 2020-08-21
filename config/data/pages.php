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
      ]
    ],
    'about' => [
        'id' => 1,
        'title' => 'About Me',
        'file' => 'about',
        'contentType' => PageRepository::CONTENT_TYPE_MARKDOWN,
    ],
    'freelancing' => [
        'id' => 2,
        'title' => 'Freelancing',
        'file' => 'freelancing',
        'contentType' => PageRepository::CONTENT_TYPE_MARKDOWN,
        'activeClients' => fn () => ProjectRepository::loadActiveClient(),
        'inactiveClients' => fn () => ProjectRepository::loadInactiveClient(),
    ],
    'imprint' => [
        'id' => 3,
        'title' => 'Imprint',
        'file' => 'imprint',
        'contentType' => PageRepository::CONTENT_TYPE_MARKDOWN,
    ],
    'engagement' => [
        'id' => 4,
        'title' => 'Engagement',
        'file' => 'readinglist',
        'contentType' => PageRepository::CONTENT_TYPE_MARKDOWN,
        'sitemap' => [
            'enabled' => false
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
    ],
    'projects/timestamp-generator' => [
        'id' => 10,
        'title' => 'Timestamp Generator',
        'file' => 'timestamp',
        'contentType' => PageRepository::CONTENT_TYPE_MARKDOWN,
        'sitemap' => [
            'enabled' => true,
        ],
    ],
    'contact' => [
        'id' => 6,
        'title' => 'Contact me',
        'file' => 'contact',
        'contentType' => PageRepository::CONTENT_TYPE_MARKDOWN,
    ],
    'projects' => [
        'id' => 7,
        'title' => 'Projects',
        'file' => 'projects',
        'contentType' => PageRepository::CONTENT_TYPE_MARKDOWN,
        'activeProjects' => fn () => ProjectRepository::loadActive(),
        'inactiveProjects' => fn () => ProjectRepository::loadInactive(),
    ],
];
