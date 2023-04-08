<?php

namespace App\Response\Question;

use App\Interfaces\ResponseInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class SuccessResponse implements ResponseInterface
{

    public function response(array $data): JsonResponse
    {
        $response = new JsonResponse($data['content'], 200, ['charset' => 'utf-8'], true);
        $response
            ->setEncodingOptions(JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        return $response;
    }

}