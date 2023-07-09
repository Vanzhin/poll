<?php

namespace App\Controller\Api\Admin;

use App\Action\Company\CreateAction;
use App\Action\Company\DeleteAction;
use App\Action\Company\ShowAction;
use App\Action\Company\UpdateAction;
use App\Entity\Company;
use App\Security\Voter\CompanyVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/admin/company', name: 'app_api_admin_company_')]
class CompanyController extends AbstractController
{
    public function __construct(
        private readonly CreateAction $createAction,
        private readonly ShowAction   $showAction,
        private readonly UpdateAction $updateAction,
        private readonly DeleteAction $deleteAction,
    )
    {
    }

    #[Route('', name: 'create', methods: ['POST'])]
    #[IsGranted('ROLE_SUPER_ADMIN')]
    public function create(Request $request): JsonResponse
    {
        return $this->createAction->run($request);
    }

    #[Route('/{id<\d+>}', name: 'update', methods: ['PUT'])]
    public function update(Company $company, Request $request): JsonResponse
    {
        if (!$this->isGranted(CompanyVoter::MANAGE, $company)) {
            throw new AccessDeniedException();
        };
        return $this->updateAction->run($company, $request);
    }

    #[Route('/{id<\d+>}', name: 'show', methods: ['GET'])]
    public function show(Company $company): JsonResponse
    {
        if (!$this->isGranted(CompanyVoter::MANAGE, $company)) {
            throw new AccessDeniedException();
        };
        return $this->showAction->run($company);
    }

    #[Route('/{id<\d+>}', name: 'delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_SUPER_ADMIN')]
    public function delete(Company $company): JsonResponse
    {
        return $this->deleteAction->run($company);
    }
}
