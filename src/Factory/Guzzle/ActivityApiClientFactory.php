<?php

namespace App\Factory\Guzzle;

class ActivityApiClientFactory
{
    public function __invoke(array $config): \GuzzleHttp\ClientInterface
    {
        $handlerStack = \GuzzleHttp\HandlerStack::create($config['handler'] ?? null);
        $config = array_merge($config, [
            'base_uri' => rtrim($config['base_uri'] ?? '', '/') . '/',
            'handler' => $handlerStack,
        ]);

        return new \GuzzleHttp\Client($config);
    }
}

