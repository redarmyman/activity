<?php

namespace App\Service;

class ActivityService
{
    public function __construct(
        private readonly \GuzzleHttp\Client $client
    ) {
    }

    public function getActivity(bool $isItRaining): array
    {
        $activity = $this->client->request('GET', 'activity', [
            'query' => [
                'type' => 'social'
            ],
        ]);

        return json_decode($activity->getBody()->getContents(), true);
    }
}

