<?php

declare(strict_types=1);

namespace App\Controller;

use Cmfcmf\OpenWeatherMap;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

final class WeatherController extends AbstractController
{
    private const CITIES = [
        'kiel' => 'Kiel,DE',
        'stuttgart' => 'Stuttgart,DE',
    ];

    #[Route(path: '/api/weather', methods: ['GET'])]
    public function getWeatherAction(OpenWeatherMap $openWeatherMap, CacheInterface $cache): JsonResponse
    {
        $result = [];
        foreach (self::CITIES as $key => $city) {
            $result[$key] = $cache->get('weather_' . $key, function (ItemInterface $item) use ($openWeatherMap, $city, $key) {
                $item->expiresAfter(5 * 60); // 5 minutes
                $weather = $openWeatherMap->getWeather($city, 'metric', 'en');
                return [
                    'temp' => $weather->temperature->getValue(),
                    'unit' => $weather->temperature->getUnit(),
                    'icon' => $weather->weather->getIconUrl(),
                    'key' => $key,
                    'name' => $weather->city->name
                ];
            });
        }
        return new JsonResponse($result);
    }
}
