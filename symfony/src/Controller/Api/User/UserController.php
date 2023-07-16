<?php

namespace App\Controller\Api\User;

use App\Controller\Api\User\Action as Actions;
use App\Entity\User\User;
use App\Security\Voter\UserVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/user', name: 'app_api_user_')]

class UserController extends AbstractController
{
    public function __construct(
        private readonly Actions\ShowAction   $showAction,
        private readonly Actions\CreateAction $createAction,
        private readonly Actions\UpdateAction $updateAction,
        private readonly Actions\DeleteAction $deleteAction,
        private readonly Actions\ListAction $listAction,
    )
    {
    }

    #[Route('/{id<\d+>}', name: 'show', methods: ['GET'])]
    public function show(User $user): JsonResponse
    {
        if (!$this->isGranted(UserVoter::VIEW, $user)) {
            throw new AccessDeniedException();
        };
        return $this->showAction->run($user);
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $user = $this->getUser();
        if (!$this->isGranted(UserVoter::CREATE, $user)) {
            throw new AccessDeniedException();
        };
// нельзя создать пользователя без компании
        if (in_array('ROLE_SUPER_ADMIN', $user->getRoles())) {
            throw new AccessDeniedException('Создание пользователя компании необходимо выполнить ее Администратором');
        };

        return $this->createAction->run($request, $user->getCompany());
    }

    #[Route('/{id<\d+>}', name: 'update', methods: ['PUT'])]
    public function update(User $user, Request $request): JsonResponse
    {
        if (!$this->isGranted(UserVoter::EDIT, $user)) {
            throw new AccessDeniedException();
        };

        return $this->updateAction->run($user, $request);
    }

    #[Route('/{id<\d+>}', name: 'delete', methods: ['DELETE'])]
    public function delete(User $user, Request $request): JsonResponse
    {
        if (!$this->isGranted(UserVoter::DELETE, $user)) {
            throw new AccessDeniedException();
        };

        return $this->deleteAction->run($user);
    }
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/list', name: 'list', methods: ['GET', 'POST'])]
    public function list(Request $request): JsonResponse
    {
        return $this->listAction->run($request);
    }
}