<?php

namespace App\Tests\Unit\Service;

use App\Service\WeatherService;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use PHPUnit\Framework\TestCase;;

class WeatherServiceTest extends TestCase
{
    public function testSuccessfulRainingRequest(): void
    {
        $response = [
            'weather' => [
                [
                    'main' => 'Rain',
                ],
            ],
        ];
        $mock = new MockHandler([
            new Response(200, [], json_encode($response))
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $weatherService = new WeatherService($client);

        $this->assertTrue($weatherService->ifRaining(0,0));
    }

    public function testSuccessfulNotRainingRequest(): void
    {
        $response = [
            'weather' => [
                [
                    'main' => 'Clouds',
                ],
            ],
        ];
        $mock = new MockHandler([
            new Response(200, [], json_encode($response))
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $weatherService = new WeatherService($client);

        $this->assertFalse($weatherService->ifRaining(0,0));
    }
}
