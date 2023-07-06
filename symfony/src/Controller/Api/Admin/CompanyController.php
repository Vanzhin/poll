<?php

namespace App\Controller\Api\Admin;

use App\Action\Company\CreateAction;
use App\Action\Company\DeleteAction;
use App\Action\Company\ShowAction;
use App\Action\Company\UpdateAction;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
    public function create(Request $request): JsonResponse
    {
        return $this->createAction->run($request);
    }

    #[Route('/{id<\d+>}', name: 'update', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        return $this->updateAction->run($id,$request);
    }

    #[Route('/{id<\d+>}', name: 'show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        return $this->showAction->run($id);
    }

    #[Route('/{id<\d+>}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        return $this->deleteAction->run($id);
    }
}
