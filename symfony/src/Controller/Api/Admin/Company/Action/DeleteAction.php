<?php

namespace App\Controller\Api\Admin\Company\Action;

use App\Action\BaseAction;
use App\Entity\Company;
use App\Service\SerializerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class DeleteAction extends BaseAction
{
    public function __construct(
        SerializerService                       $serializer,
        private readonly EntityManagerInterface $entityManager,
    )
    {
        parent::__construct($serializer);
    }

    public function run(Company $company): JsonResponse
    {
        $this->entityManager->remove($company);
        $this->entityManager->flush();

        return $this->successResponse($company, ['admin_user']);

    }
}