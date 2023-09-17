<?php

namespace App\Controller\Api\Admin\Protocol\Action;

use App\Controller\Api\BaseAction\NewBaseAction;
use App\Entity\Protocol\Protocol;
use App\Service\FileUploader;
use App\Service\SerializerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class DeleteAction extends NewBaseAction
{
    public function __construct(
        SerializerService                       $serializer,
        private readonly EntityManagerInterface $entityManager,
        private readonly FileUploader           $protocolFileUploader
    )
    {
        parent::__construct($serializer);
    }

    public function run(Protocol $protocol): JsonResponse
    {
        $this->entityManager->remove($protocol);
        $this->entityManager->flush();
        if ($protocol->getFile()) {
            $this->protocolFileUploader->delete($protocol->getFile());
        }

        return $this->successResponse($protocol, ['admin_protocol'], 'Протокол удален.');
    }
}