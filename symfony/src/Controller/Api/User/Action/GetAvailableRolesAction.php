<?php

namespace App\Controller\Api\User\Action;

use App\Controller\Api\BaseAction\NewBaseAction;
use App\Entity\User\User;
use App\Service\RoleService;
use App\Service\SerializerService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;

class GetAvailableRolesAction extends NewBaseAction
{
    public function __construct(
        SerializerService $serializer,
        private readonly RoleService $roleService,

    )
    {
        parent::__construct($serializer);
    }

    public function run(UserInterface $user): JsonResponse
    {
        $availableRoles = $this->roleService->getAllowedAliasesToAssign($user);
        return $this->successResponse($availableRoles);
    }
}