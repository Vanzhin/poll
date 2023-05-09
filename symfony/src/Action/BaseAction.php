<?php

namespace App\Action;

use App\Service\SerializerService;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class BaseAction
{

    public function __construct(private readonly SerializerService $serializer)
    {
    }

    public function errorResponse(array|string $data, int $status = 422): JsonResponse
    {
        if (is_string($data)) {
            $data = ["error" => $data];
        }
        return new JsonResponse($data, $status, []);

    }

    public function successResponse(array|object $object, array $groups = [], string $format = 'json', bool $json = true, int $status = 200): JsonResponse
    {
        $data = $this->serializer->serializeObject($object, $format, $groups);
        $response = new JsonResponse($data, $status, [], $json);
        return $response;
    }

    public function successFileResponse(array $data): Response
    {
        $response = new Response($data['content']);
        $fileName = $data['fileName'] ?? 'test' . '.xml';
        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            $fileName
        );

        $response->headers->set('Content-Disposition', $disposition);
        return $response;
    }

}