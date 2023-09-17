<?php

namespace App\Controller\Api\Admin\Protocol\Action;

use App\Console\Contract\GenerateProtocolInterface;
use App\Controller\Api\BaseAction\NewBaseAction;
use App\Entity\Protocol\Protocol;
use App\Service\SerializerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class GenerateAction extends NewBaseAction
{


    public function __construct(
        SerializerService                          $serializer,
        private readonly GenerateProtocolInterface $generateProtocol,
        private readonly EntityManagerInterface    $em,

    )
    {
        parent::__construct($serializer);
    }

    public function run(Protocol ...$protocols): JsonResponse
    {
        foreach ($protocols as $protocol) {
            $protocol->setFile($this->generateProtocol->generate($protocol));
            $this->em->persist($protocol);
        }

        $this->em->flush();

        return $this->successResponse($protocol, ['admin_protocol'], 'Протокол сгенерирован.');
    }
}