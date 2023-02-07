<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    #[Route('/api/auth/account', name: 'app_api_auth_account')]
    public function index(): JsonResponse
    {
        return $this->json(
            $this->getUser(),
            200,
            ['charset=utf-8'],
            ['groups' => 'account'],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}
