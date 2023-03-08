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
        if (null === $request->query->get('lat') || null === $request->query->get('lon')) {
            return new JsonResponse(
                ['error' => 'Missing lat or lon parameter'],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        return new JsonResponse([
            'lat' => $request->query->get('lat'),
            'lon' => $request->query->get('lon')
        ]);
    }
}

