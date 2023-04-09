<?php

namespace App\Response\Question;

use App\Interfaces\ResponseInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class SuccessResponse implements ResponseInterface
{

    public function response(array $data, bool $json = true): JsonResponse
    {
        $response = new JsonResponse($data['content'], 200, ['charset' => 'utf-8'], $json);

//        $response->setEncodingOptions(JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        return $response;
    }



}