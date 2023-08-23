<?php

namespace App\Controller\Api\Group\Action;

use App\Controller\Api\BaseAction\NewBaseAction;
use App\Entity\Group;
use App\Service\SerializerService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;

class ShowAction extends NewBaseAction
{
    public function __construct(
        SerializerService $serializer,
    )
    {
        parent::__construct($serializer);
    }

    public function run(Group $group): JsonResponse
    {
        return $this->successResponse($group, ['admin_group']);
    }
}