<?php

declare(strict_types=1);

use App\Controller\PageController;
use App\Repository\PageRepository;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    $pages = require_once PROJECT_ROOT . '/config/data/pages.php';

    foreach ($pages as $route => $data) {
        $options = [
            'sitemap' => $sitemap = array_merge([
                'priority' => 0.7
            ], $data['sitemap'] ?? []),
        ];
        if (isset($data['sitemap'], $data['sitemap']['enabled']) && $data['sitemap']['enabled'] === false) {
            unset($options['sitemap']);
        }
        $action = match($data['contentType']){
        PageRepository::CONTENT_TYPE_TWIG => 'renderAction',
            default => 'pageAction',
        };
        $routes->add($route, DIRECTORY_SEPARATOR . $route)
            ->controller([PageController::class, $action])
            ->methods(['GET', 'HEAD'])
            ->options($options);
    }
};
