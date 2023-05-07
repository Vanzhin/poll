<?php

namespace App\Action\Test;

use App\Action\BaseAction;
use App\Entity\MinTrudTest;
use App\Repository\MinTrudTestRepository;
use App\Service\SerializerService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GetMinTrudTest extends BaseAction
{
    public function __construct(
        private readonly MinTrudTestRepository  $minTrudTestRepository,
        private readonly EntityManagerInterface $em,
        private readonly SerializerService      $serializer
    )
    {
        parent::__construct($serializer);
    }

    public function getAll(): JsonResponse
    {
        return $this->successResponse($this->minTrudTestRepository->findAllSortedByTitle(), ['admin']);

    }

    public function get(Request $request): JsonResponse
    {
        $test = $this->em->find(MinTrudTest::class, $request->attributes->get('_route_params', [])['id']);

        if (!$test) {
            return $this->errorResponse(['error' => 'Тест с таким идентификатором не найден']);

        }

        return $this->successResponse($test, ['admin']);

    }


}