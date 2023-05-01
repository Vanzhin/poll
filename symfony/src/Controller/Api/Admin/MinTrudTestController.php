<?php

namespace App\Controller\Api\Admin;

use App\Action\Test\CreateMinTrudTest;
use App\Action\Test\DeleteMinTrudTest;
use App\Action\Test\GetMinTrudTest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MinTrudTestController extends AbstractController
{
    #[Route('/api/admin/min_trud_test', name: 'app_api_admin_min_trud_test')]
    public function index(GetMinTrudTest $getTrudTest): JsonResponse
    {
        return $getTrudTest->getAll();
    }

    #[Route('/api/admin/min_trud_test/{id}', name: 'app_api_admin_min_trud_test_show', methods: 'GET')]
    public function show(Request $request, GetMinTrudTest $getTrudTest): JsonResponse
    {
        return $getTrudTest->get($request);
    }

    #[Route('/api/admin/min_trud_test/', name: 'app_api_admin_min_trud_test_create', methods: 'POST')]
    public function create(Request $request, CreateMinTrudTest $createTrudTest): JsonResponse
    {
        return $createTrudTest->createOrUpdate($request);
    }

    #[Route('/api/admin/min_trud_test/{id}', name: 'app_api_admin_min_trud_test_update', methods: 'PUT')]
    public function update(Request $request, CreateMinTrudTest $createTrudTest): JsonResponse
    {
        return $createTrudTest->createOrUpdate($request);
    }

    #[Route('/api/admin/min_trud_test/{id}', name: 'app_api_admin_min_trud_test_delete', methods: 'DELETE')]
    public function delete(Request $request, DeleteMinTrudTest $deleteTrudTest): JsonResponse
    {
        return $deleteTrudTest->delete($request);
    }


}
