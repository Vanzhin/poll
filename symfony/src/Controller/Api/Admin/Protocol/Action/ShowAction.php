<?php

namespace App\Controller\Api\Admin\Protocol\Action;

use App\Controller\Api\BaseAction\NewBaseAction;
use App\Entity\Protocol;
use App\Service\SerializerService;
use Symfony\Component\HttpFoundation\JsonResponse;

class ShowAction extends NewBaseAction
{
    public function __construct(SerializerService $serializer)
    {
        parent::__construct($serializer);
    }

    public function run(Protocol $protocol): JsonResponse
    {
        return $this->successResponse($protocol, ['admin_protocol']);
    }
}