<?php

namespace App\Controller\Api\Ticket\Action;

use App\Controller\Api\BaseAction\NewBaseAction;
use App\Entity\Ticket;
use App\Service\SerializerService;
use Symfony\Component\HttpFoundation\JsonResponse;

class BreadcrumbsAction extends NewBaseAction
{
    public function __construct(
        SerializerService $serializer,
    )
    {
        parent::__construct($serializer);
    }

    public function run(Ticket $ticket): JsonResponse
    {
        return $this->successResponse($ticket, ['breadcrumbs']);
    }
}