<?php

namespace App\Controller\Api;

use App\Action\Security\LinkLoginAction;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    #[Route('/api/login_link/{email}', name: 'app_api_login_link')]
    public function index(Request $request, LinkLoginAction $action): JsonResponse
    {
        return $action->sendLink($request);
    }

    #[Route('/api/login_link_check', name: 'app_api_login_link_check')]
    public function check(Request $request)
    {
        throw new \LogicException('This code should never be reached');
    }


}
