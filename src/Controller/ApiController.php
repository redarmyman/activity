<?php

namespace App\Controller;

use App\Service\WeatherService;
use App\Service\MessageGenerator;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    #[Route('/v1/isitraining', name: 'api_is_it_raining')]
    public function isItRaining(WeatherService $weather, Request $request): JsonResponse
    {
        if (null === $request->query->get('lat') || null === $request->query->get('lon')) {
            return $this->getRainingResponse(JsonResponse::HTTP_BAD_REQUEST, 'Missing lat or lon parameter');
        }

        try {
            $ifRaining = $weather->ifRaining($request->query->get('lat'), $request->query->get('lon'));
        } catch(ClientException|ServerException $e) {
            return $this->getRainingResponse(
                $e->getResponse()->getStatusCode(),
                json_decode((string) $e->getResponse()->getBody(), true)['message']
            );
        }

        return $this->getRainingResponse(JsonResponse::HTTP_OK, '', $ifRaining);
    }

    private function getRainingResponse(int $code, string $message, bool $ifRaining = false): JsonResponse
    {
        $response = ['code' => $code];

        if ($code > 399) {
            $response['message'] = $message;
        } else {
            $response['isItRaining'] = $ifRaining;
        }

        return new JsonResponse($response, $code);
    }
}

