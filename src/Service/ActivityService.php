<?php

namespace App\Service;

class ActivityService
{
    public function __construct(
        private readonly \GuzzleHttp\Client $client,
        private readonly ActivityQueryService $queryService
    ) {
    }

    public function getActivity(bool $isItRaining): array
    {
        $activity = $this->client->request('GET', 'activity', [
            'query' => $this->queryService->getQuery($isItRaining),
        ]);

        return json_decode($activity->getBody()->getContents(), true);
    }
}

