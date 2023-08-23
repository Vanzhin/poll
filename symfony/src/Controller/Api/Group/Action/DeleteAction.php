<?php

namespace App\Controller\Api\Group\Action;

use App\Controller\Api\BaseAction\NewBaseAction;
use App\Entity\Group;
use App\Service\SerializerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class DeleteAction extends NewBaseAction
{
    public function __construct(
        SerializerService                       $serializer,
        private readonly EntityManagerInterface $entityManager,
    )
    {
        parent::__construct($serializer);
    }

    public function run(Group $group): JsonResponse
    {
        $this->entityManager->persist($group);
        $this->entityManager->flush();

        return $this->successResponse($group, ['admin_group'], 'Группа удалена');
    }
}