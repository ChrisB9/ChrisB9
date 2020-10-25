<?php

declare(strict_types=1);

use App\Controller\PageController;
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
        $routes->add($route, ['en' => '/' . $route, 'de' => '/de/' . $route])
            ->controller([PageController::class, 'pageAction'])
            ->methods(['GET', 'HEAD'])
            ->options($options);
    }
};
