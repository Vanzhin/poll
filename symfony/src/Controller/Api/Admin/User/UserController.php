<?php

namespace App\Controller\Api\Admin\User;

use App\Controller\Api\Admin\User\Action as Actions;
use App\Entity\User\User;
use App\Security\Voter\UserVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/admin/user', name: 'app_api_admin_user_')]
class UserController extends AbstractController
{
    public function __construct(
        private readonly Actions\ShowAction $showAction
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

}