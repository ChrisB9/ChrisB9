<?php

declare(strict_types=1);

namespace App\Factory;

use Cmfcmf\OpenWeatherMap;
use GuzzleHttp\Client;
use Http\Factory\Guzzle\RequestFactory;

final class OpenWeatherMapFactory
{
    public function __invoke(string $apiKey)
    {
        return new OpenWeatherMap($apiKey, new Client([]), new RequestFactory());
    }
}
