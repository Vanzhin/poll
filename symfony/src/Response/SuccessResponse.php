<?php

namespace App\Response;

use App\Interfaces\ResponseInterface;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Response;

class SuccessResponse implements ResponseInterface
{

    public function response(array $data): Response
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