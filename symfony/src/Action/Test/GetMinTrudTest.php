<?php

namespace App\Action\Test;

use App\Action\BaseAction;
use App\Repository\MinTrudTestRepository;
use App\Service\SerializerService;
use Symfony\Component\HttpFoundation\JsonResponse;

class GetMinTrudTest extends BaseAction
{
    public function __construct(
        private readonly MinTrudTestRepository $minTrudTestRepository,
        private readonly SerializerService     $serializer
    )
    {
        parent::__construct($serializer);
    }

    public function getAll(): JsonResponse
    {
        return $this->successResponse($this->minTrudTestRepository->findAllSortedByTitle(), ['admin']);

    }
}