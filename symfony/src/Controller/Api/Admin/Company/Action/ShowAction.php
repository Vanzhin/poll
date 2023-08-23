<?php

namespace App\Controller\Api\Admin\Company\Action;

use App\Controller\Api\BaseAction\NewBaseAction;
use App\Entity\Company;
use App\Service\SerializerService;
use Symfony\Component\HttpFoundation\JsonResponse;

class ShowAction extends NewBaseAction
{
    public function __construct(SerializerService $serializer)
    {
        parent::__construct($serializer);
    }

    public function run(Company $company): JsonResponse
    {
        return $this->successResponse($company, ['admin_user']);

    }
}