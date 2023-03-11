<?php

namespace App\Service;

class ActivityQueryService
{
    public function getQuery(bool $isItRaining): array
    {
        if ($isItRaining) {
            $activities = [
                'education',
                'recreational',
                'diy',
                'cooking',
                'relaxation',
                'music',
                'busywork',
            ];

            return [
                'type' => $activities[array_rand($activities)],
                'participants' => 1,
            ];
        }

        return [
           'type' => 'social',
           'participants' => rand(2,4),
        ];
    }
}

