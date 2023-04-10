<?php

namespace App\Response\Question;

use App\Interfaces\ResponseInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class ErrorResponse implements ResponseInterface
{

    public function response(array $data, int $code = 422): JsonResponse
    {
        return new JsonResponse($data, $code, ['charset=utf-8']);

    }
}