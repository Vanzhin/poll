<?php

namespace App\Action\Test;

use App\Handler\TestHandler;
use App\Repository\MinTrudTestRepository;
use App\Response\Question\ErrorResponse;
use App\Response\Question\SuccessResponse;
use Symfony\Component\HttpFoundation\JsonResponse;

class GetMinTrudTest
{
    public function __construct(private readonly MinTrudTestRepository $minTrudTestRepository,
                                private readonly TestHandler           $testHandler,
                                private readonly ErrorResponse         $errorResponse,
                                private readonly SuccessResponse       $successResponse)
    {
    }

    public function getAll(): JsonResponse
    {
        return $this->successResponse
            ->response(['content' => $this->testHandler->getAll($this->minTrudTestRepository->findAllSortedByTitle(), 'json', ['admin'])]);

    }
}