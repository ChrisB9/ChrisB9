<?php

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    $routes->import('@FrameworkBundle/Resources/config/routing/errors.xml')
        ->prefix('/_error')
    ;
};
