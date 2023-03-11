<?php

namespace App\Tests\Unit\Service;

use App\Service\ActivityService;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use PHPUnit\Framework\TestCase;;

class ActivityServiceTest extends TestCase
{
    public function testSuccessfulActivityRequest(): void
    {
        $response = [
            'activity' => 'Buy a new jacket',
            'type' => 'social'
        ];
        $mock = new MockHandler([
            new Response(200, [], json_encode($response))
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $activityService = new ActivityService($client);

        $activity = $activityService->getActivity(true);

        $this->assertSame('Buy a new jacket', $activity['activity']);
        $this->assertSame('social', $activity['type']);
    }
}

