<?php

namespace App\Controller\Api\Test\Action;

use App\Controller\Api\BaseAction\NewBaseAction;
use App\Entity\Test;
use App\Service\SerializerService;
use Symfony\Component\HttpFoundation\JsonResponse;

class BreadcrumbsAction extends NewBaseAction
{
    public function __construct(
        SerializerService $serializer,
    )
    {
        parent::__construct($serializer);
    }

    public function run(Test $test): JsonResponse
    {
        return $this->successResponse($test, ['breadcrumbs']);
    }
}