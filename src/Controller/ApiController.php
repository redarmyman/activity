<?php

namespace App\Controller;

use App\Service\ActivityService;
use App\Service\WeatherService;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    public function __construct(private WeatherService $weather)
    {}

    /**
     * @OA\Parameter(
     *     name="lat",
     *     in="query",
     *     description="Latitude",
     *     required=true,
     *     @OA\Schema(type="string", default="51.110743")
     * )
     * @OA\Parameter(
     *     name="lon",
     *     in="query",
     *     description="Longitude",
     *     required=true,
     *     @OA\Schema(type="string", default="17.035002")
     * )
     */
    #[Route('/v1/isitraining', name: 'api_is_it_raining', methods: ['GET'])]
    public function isItRaining(Request $request): JsonResponse
    {
        return $this->isItRainingResponse($request->query->get('lat'), $request->query->get('lon'));
    }

    /** 
     * @OA\Parameter(
     *     name="lat",
     *     in="query",
     *     description="Latitude",
     *     required=true,
     *     @OA\Schema(type="string", default="51.110743")
     * )
     * @OA\Parameter(
     *     name="lon",
     *     in="query",
     *     description="Longitude",
     *     required=true,
     *     @OA\Schema(type="string", default="17.035002")
     * )
     */
    #[Route('/v1/whattodo', name: 'api_activity', methods: ['GET'])]
    public function activity(Request $request, ActivityService $activityService): JsonResponse
    {
        $isItRainingResponse = $this->isItRaining($request);

        $isItRaining = json_decode($isItRainingResponse->getContent(), true);

        if ($isItRaining['code'] > 399) {
            return $isItRainingResponse;
        }

        $activity = $activityService->getActivity($isItRaining['isItRaining']);

        return new JsonResponse($activity);
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

