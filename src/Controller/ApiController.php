<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApiController
{
    #[Route('/v1/isitraining', name: 'api_is_it_raining')]
    public function isItRaining(Request $request): JsonResponse
    {
        return new JsonResponse([
            'lat' => $request->query->get('lat'),
            'lon' => $request->query->get('lon')
        ]);
    }
}

