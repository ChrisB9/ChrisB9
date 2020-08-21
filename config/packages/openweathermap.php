<?php

declare(strict_types=1);

use App\Factory\OpenWeatherMapFactory;
use Cmfcmf\OpenWeatherMap;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return function (ContainerConfigurator $configurator) {
    $configurator->services()
        ->set(OpenWeatherMap::class)
        ->factory(service(OpenWeatherMapFactory::class))
        ->args(['%env(resolve:WEATHER_API)%']);
};
