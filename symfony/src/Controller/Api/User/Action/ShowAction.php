<?php

namespace App\Controller\Api\User\Action;

use App\Controller\Api\BaseAction\NewBaseAction;
use App\Entity\User\User;
use App\Service\SerializerService;
use Symfony\Component\HttpFoundation\JsonResponse;

class ShowAction extends NewBaseAction
{
    public function __construct(SerializerService $serializer)
    {
        parent::__construct($serializer);
    }

    public function run(User $user): JsonResponse
    {
        return $this->successResponse($user, ['user_editable']);

    }
}