<?php

namespace App\Controller\Api\User;

use App\Controller\Api\User\Action\CreateCompanyUserAction;
use App\Controller\Api\User\Action\ShowAction;
use App\Entity\Company;
use App\Entity\User\User;
use App\Security\Voter\CompanyVoter;
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
        private readonly ShowAction              $showAction,
        private readonly CreateCompanyUserAction $createAction,
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

    #[Route('/company/{id<\d+>}', name: 'create_in_company', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function create(Request $request, Company $company): JsonResponse
    {
        if (!$this->isGranted(CompanyVoter::MANAGE, $company)) {
            throw new AccessDeniedException();
        };


        return $this->createAction->run($request, $company);
    }

}