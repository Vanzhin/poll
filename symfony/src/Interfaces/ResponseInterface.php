<?php

namespace App\Interfaces;

use Symfony\Component\HttpFoundation\Response;

interface ResponseInterface
{
    /**
     * @param array $data
     * @return Response
     */
    public function response(array $data): Response;

}