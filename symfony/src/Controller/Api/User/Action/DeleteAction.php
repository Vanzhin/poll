<?php

namespace App\Controller\Api\User\Action;

use App\Controller\Api\BaseAction\NewBaseAction;
use App\Entity\User\User;
use App\Service\SerializerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class DeleteAction extends NewBaseAction
{
    public function __construct(
        SerializerService $serializer,
        private readonly EntityManagerInterface $entityManager,
    )
    {
        parent::__construct($serializer);
    }

    public function run(User $user): JsonResponse
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
        return $this->successResponse($user, ['user_editable']);
    }
}