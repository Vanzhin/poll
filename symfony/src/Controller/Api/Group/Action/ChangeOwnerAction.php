<?php

namespace App\Controller\Api\Group\Action;

use App\Controller\Api\BaseAction\NewBaseAction;
use App\Entity\Group;
use App\Repository\Interfaces\UserRepositoryInterface;
use App\Response\AppException;
use App\Service\RoleService;
use App\Service\SerializerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ChangeOwnerAction extends NewBaseAction
{
    public function __construct(
        SerializerService                        $serializer,
        private readonly EntityManagerInterface  $entityManager,
        private readonly RoleService             $service,
        private readonly UserRepositoryInterface $userRepository,
    )
    {
        parent::__construct($serializer);
    }

    public function run(Request $request, Group $group): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $newOwner = current($this->userRepository->findAllById($data['owner']));

        $allowedUsers = $this->userRepository->findGroupOwnerToChange($group, $this->service->getAllowedRolesToAssign());

        if ($newOwner === $group->getOwner()) {
            throw new AppException(sprintf('Пользователь с идентификатором %d уже является владельцем группы.', $newOwner->getId()));
        }
        if (!in_array($newOwner, $allowedUsers)) {
            throw new AppException(sprintf('Вам не разрешено назначать пользователя с идентификатором %d./ Пользователь не может быть назначен владельцем группы.', $newOwner->getId()), 403);
        }
        $group->setOwner($newOwner);
        $this->entityManager->persist($group);
        $this->entityManager->flush();

        return $this->successResponse($group, ['admin_group'], 'Группа обновлена.');
    }
}