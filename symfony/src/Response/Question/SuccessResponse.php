<?php

namespace App\Response\Question;

use App\Interfaces\ResponseInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class SuccessResponse implements ResponseInterface
{

    public function response(array $data, bool $json = true, int $status = 200): JsonResponse
    {
        $response = new JsonResponse($data['content'], $status, ['charset' => 'utf-8'], $json);
        $response->setEncodingOptions(JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        return $response;
    }



}