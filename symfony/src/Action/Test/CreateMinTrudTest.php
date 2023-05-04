<?php

namespace App\Action\Test;

use App\Action\BaseAction;
use App\Entity\MinTrudTest;
use App\Factory\MinTrudTest\MinTrudTestBuilder;
use App\Service\SerializerService;
use App\Service\ValidationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CreateMinTrudTest extends BaseAction
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly MinTrudTestBuilder     $builder,
        private readonly ValidationService      $validation,
        private readonly SerializerService      $serializer

    )
    {
        parent::__construct($serializer);
    }


    public function createOrUpdate(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            $testToUpdate = $this->em->find(MinTrudTest::class, $request->attributes->get('_route_params', [])['id']);

            if (!$testToUpdate) {
                return $this->errorResponse(['error' => 'Тест с таким идентификатором не найден']);

            }
            $test = $this->builder->createOrUpdateMinTrudTest($data, $testToUpdate);
            $errors = $this->validation->validate($test);
            if (!empty($errors)) {
                return $this->errorResponse(['error' => $errors]);

            };
            $this->em->persist($test);
            $this->em->flush();
            return $this->successResponse([
                'message' => 'МинТрудТест создан',
                'data' => $test
            ], ['admin']);

        } catch (\Exception|\Error $e) {
            return $this->errorResponse(['error' => $e->getMessage()]);
        }
    }
}