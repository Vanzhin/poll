<?php

namespace App\Controller\Api\Registration;

use App\Controller\Api\Registration\Action\UserRegisterAction;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    public function __construct(private readonly UserRegisterAction $registerAction,)
    {
    }

    #[Route('/api/register', name: 'app_api_register', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        return $this->registerAction->run($data);
    }
}
