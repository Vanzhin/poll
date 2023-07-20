<?php

namespace App\Controller\Api\Profile;

use App\Controller\Api\Profile\Action\InfoAction;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class ProfileController extends AbstractController
{
    #[Route('/api/profile/info', name: 'app_api_profile_info', methods: ['GET'])]
    public function info(InfoAction $action): JsonResponse
    {
        return $action->run();
    }
}
