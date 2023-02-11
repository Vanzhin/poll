<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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

    #[Route('/api/auth/user', name: 'app_api_auth_user')]
    public function getUserInfo(): JsonResponse
    {
        return $this->json(
            $this->getUser(),
            200,
            ['charset=utf-8'],
            ['groups' => 'user'],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}
