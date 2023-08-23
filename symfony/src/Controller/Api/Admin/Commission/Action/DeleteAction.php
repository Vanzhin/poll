<?php
declare(strict_types=1);

namespace App\Controller\Api\Admin\Commission\Action;

use App\Controller\Api\BaseAction\NewBaseAction;
use App\Entity\Commission\Commission;
use App\Service\SerializerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class DeleteAction extends NewBaseAction
{
    public function __construct(
        SerializerService                       $serializer,
        private readonly EntityManagerInterface $entityManager
    )
    {
        parent::__construct($serializer);
    }

    public function run(Commission $commission): JsonResponse
    {
        $this->entityManager->remove($commission);
        $this->entityManager->flush();
        return $this->successResponse($commission, ['admin_commission'], 'Комиссия удалена.');
    }
}