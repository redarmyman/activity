<?php

namespace App\Controller;

use App\Service\WeatherService;
use App\Service\MessageGenerator;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    public function __construct(private WeatherService $weather)
    {}

    #[Route('/v1/isitraining', name: 'api_is_it_raining')]
    public function isItRaining(Request $request): JsonResponse
    {
        return $this->isItRainingResponse($request->query->get('lat'), $request->query->get('lon'));
    }

    #[Route('/v1/whattodo', name: 'api_activity')]
    public function activity(Request $request): JsonResponse
    {
        $isItRainingResponse = $this->isItRaining($request);

        $isItRaining = json_decode($isItRainingResponse->getContent(), true);

        if ($isItRaining['code'] > 399) {
            return $isItRainingResponse;
        }

        dd($isItRaining);
    }

    private function isItRainingResponse(?string $lat, ?string $lon): JsonResponse
    {
        if (null === $lat || null === $lon) {
            return $this->getRainingResponse(JsonResponse::HTTP_BAD_REQUEST, 'Missing lat or lon parameter');
        }

        try {
            $ifRaining = $this->weather->ifRaining($lat, $lon);
        } catch(ClientException|ServerException $e) {
            return $this->getRainingResponse(
                $e->getResponse()->getStatusCode(),
                json_decode((string) $e->getResponse()->getBody(), true)['message']
            );
        } catch (\Exception $e) {
            return $this->getRainingResponse(JsonResponse::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
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

