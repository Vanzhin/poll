<?php

namespace App\Controller\Api\Admin\Company\Action;

use App\Action\BaseAction;
use App\Entity\Company;
use App\Service\SerializerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class ShowAction extends BaseAction
{
    public function __construct(SerializerService $serializer)
    {
        parent::__construct($serializer);
    }

    public function run(Company $company): JsonResponse
    {
        try {
            return $this->successResponse($company, ['admin_user']);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}