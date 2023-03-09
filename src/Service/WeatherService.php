<?php

namespace App\Service;

class WeatherService
{
    public function __construct(
        private readonly \GuzzleHttp\Client $client
    ) {
    }

    public function ifRaining(string $lat, string $lon): bool
    {
        $weather = $this->client->request('GET', 'weather', [
            'query' => [
                'lat' => $lat,
                'lon' => $lon,
                'exclude' => 'minutely,hourly,daily,alerts',
            ],
        ]);

        return $this->isItRaining(json_decode($weather->getBody()->getContents(), true));
    }

    private function isItRaining(array $weather): bool
    {
        return $weather['weather'][0]['main'] === 'Rain';
    }
}

