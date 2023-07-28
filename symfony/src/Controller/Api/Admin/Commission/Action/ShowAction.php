<?php
declare(strict_types=1);

namespace App\Controller\Api\Admin\Commission\Action;

use App\Controller\Api\BaseAction\NewBaseAction;
use App\Entity\Commission\Commission;
use App\Service\SerializerService;
use Symfony\Component\HttpFoundation\JsonResponse;

class ShowAction extends NewBaseAction
{
    public function __construct(SerializerService $serializer)
    {
        parent::__construct($serializer);
    }

    public function run(Commission $commission): JsonResponse
    {
        return $this->successResponse($commission, ['admin_commission']);
    }
}