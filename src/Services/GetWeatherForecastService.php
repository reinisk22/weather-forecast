<?php

declare(strict_types=1);

namespace App\Services;

use App\Services\Exceptions\RequestFailedException;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Contracts\HttpClient\HttpClientInterface;

use function Safe\json_decode;

class GetWeatherForecastService
{
    public function __construct(
        private HttpClientInterface $client,
        private string $apiUrl,
        private string $secretKey
    ) {
    }

    #[ArrayShape([
            'weather_main' => 'array',
            'temp'         => 'mixed',
            'feels_like'   => 'mixed',
            'temp_min'     => 'mixed',
            'temp_max'     => 'mixed',
            'pressure'     => 'mixed',
            'humidity'     => 'mixed',
            'visibility'   => 'mixed',
            'wind_speed'   => 'mixed',
        ])]
    public function getWeatherForecast(float $latitude, float $longitude): array
    {
        $response = $this->client->request(
            'GET',
            sprintf(
                '%s/data/2.5/weather?lat=%s&lon=%s&appid=%s',
                $this->apiUrl,
                $latitude,
                $longitude,
                $this->secretKey,
            ),
        );

        if (200 !== $response->getStatusCode()) {
            throw RequestFailedException::badRequest($response);
        }

        $result = json_decode($response->getContent(), true);

        $weather = [];
        foreach ($result['weather'] as $weatherData) {
            $weather[] = [
                'weather_group' => $weatherData['main'],
                'weather_description' => $weatherData['description'],
            ];
        }

        return [
            'weather_main' => $weather,
            'temp'         => $result['main']['temp'],
            'feels_like'   => $result['main']['feels_like'],
            'temp_min'     => $result['main']['temp_min'],
            'temp_max'     => $result['main']['temp_max'],
            'pressure'     => $result['main']['pressure'],
            'humidity'     => $result['main']['humidity'],
            'visibility'   => $result['visibility'],
            'wind_speed'   => $result['wind']['speed'],
        ];
    }
}