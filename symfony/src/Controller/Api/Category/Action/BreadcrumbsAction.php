<?php

namespace App\Controller\Api\Category\Action;

use App\Controller\Api\BaseAction\NewBaseAction;
use App\Entity\Category;
use App\Service\SerializerService;
use Symfony\Component\HttpFoundation\JsonResponse;

class BreadcrumbsAction extends NewBaseAction
{
    public function __construct(SerializerService $serializer)
    {
        parent::__construct($serializer);
    }

    public function run(Category $category): JsonResponse
    {
        return $this->successResponse($category, ['breadcrumbs']);
    }
}