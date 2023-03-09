<?php

namespace App\Factory\Guzzle;

class WeatherApiClientFactory
{
    public function __invoke(array $config, string $apiKey): \GuzzleHttp\ClientInterface
    {
        $handlerStack = \GuzzleHttp\HandlerStack::create($config['handler'] ?? null);
        $config = array_merge($config, [
            'base_uri' => rtrim($config['base_uri'] ?? '', '/') . '/',
            'handler' => $handlerStack,
        ]);
        $handlerStack->unshift(\GuzzleHttp\Middleware::mapRequest(static function (\GuzzleHttp\Psr7\Request $request) use ($apiKey) {
            return $request->withUri(\GuzzleHttp\Psr7\Uri::withQueryValue($request->getUri(), 'appid', $apiKey));
        }));

        return new \GuzzleHttp\Client($config);
    }
}

