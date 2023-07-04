<?php

namespace App\Controller\Api\Admin;

use App\Action\Company\CreateAction;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CompanyController extends AbstractController
{
    public function __construct(private readonly CreateAction $createAction)
    {
    }

    #[Route('/api/admin/company', name: 'app_api_admin_company', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        return $this->createAction->create($request);
    }
}
