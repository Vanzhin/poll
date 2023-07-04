<?php

namespace App\Action\Company;

use App\Action\BaseAction;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CreateAction extends BaseAction
{
    public function create(Request $request): JsonResponse
    {
        return $this->successResponse($request);

    }

}