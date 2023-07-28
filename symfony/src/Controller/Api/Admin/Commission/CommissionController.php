<?php

namespace App\Controller\Api\Admin\Commission;

use App\Controller\Api\Admin\Commission\Action as Actions;
use App\Entity\Commission\Commission;
use App\Security\Voter\CommissionVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/api/admin/commission', name: 'app_api_admin_commission_')]
class CommissionController extends AbstractController
{

    public function __construct(
        private readonly Actions\CreateAction $createAction,
        private readonly Action\UpdateAction  $updateAction,
        private readonly Actions\ShowAction   $showAction,
        private readonly Actions\DeleteAction $deleteAction,
    )
    {
    }

    #[Route('/{id<\d+>}', name: 'show', methods: ['GET'])]
    public function show(Commission $commission): JsonResponse
    {
        if (!$this->isGranted(CommissionVoter::VIEW, $commission)) {
            throw new AccessDeniedException();
        };
        return $this->showAction->run($commission);
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $user = $this->getUser();

        if (in_array('ROLE_SUPER_ADMIN', $user->getRoles())) {
            throw new AccessDeniedException('Создание комиссии компании необходимо выполнить ее Администратором');
        };
        return $this->createAction->run($request, $user->getCompany());
    }

    #[Route('/{id<\d+>}', name: 'update', methods: ['PUT'])]
    public function update(Commission $commission, Request $request): JsonResponse
    {
        if (!$this->isGranted(CommissionVoter::EDIT, $commission)) {
            throw new AccessDeniedException();
        };
        return $this->updateAction->run($request, $commission);
    }

    #[Route('/{id<\d+>}', name: 'delete', methods: ['DELETE'])]
    public function delete(Commission $commission): JsonResponse
    {
        if (!$this->isGranted(CommissionVoter::DELETE, $commission)) {
            throw new AccessDeniedException();
        };
        return $this->deleteAction->run($commission);
    }
}