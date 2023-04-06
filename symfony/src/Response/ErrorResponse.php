<?php

namespace App\Response;

use App\Interfaces\ResponseInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class ErrorResponse implements ResponseInterface
{

    public function response(array $data): JsonResponse
    {
        return new JsonResponse($data, 422, ['charset=utf-8']);

    }
}