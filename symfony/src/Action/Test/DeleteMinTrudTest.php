<?php

namespace App\Action\Test;

use App\Action\BaseAction;
use App\Entity\MinTrudTest;
use App\Service\SerializerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DeleteMinTrudTest extends BaseAction
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly SerializerService      $serializer

    )
    {
        parent::__construct($serializer);
    }


    public function delete(Request $request): JsonResponse
    {
        try {
            $testToDelete = $this->em->find(MinTrudTest::class, $request->attributes->get('_route_params', [])['id']);

            if (!$testToDelete) {
                return $this->errorResponse(['error' => 'Тест с таким идентификатором не найден']);
            }

            $this->em->remove($testToDelete);
            $this->em->flush();
            return $this->successResponse([
                'message' => 'МинТрудТест удален',
                'data' => $testToDelete
            ], ['admin']);

        } catch (\Exception $e) {
            return $this->errorResponse(['error' => $e->getMessage()]);

        }
    }
}