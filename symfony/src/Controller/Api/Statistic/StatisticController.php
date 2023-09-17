<?php

namespace App\Controller\Api\Statistic;

use App\Controller\Api\Statistic\Action as Actions;
use App\Controller\BaseController;
use App\Repository\Interfaces\UserRepositoryInterface;
use App\Repository\Test\Filter\UserTest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('/api/statistic', name: 'app_api_statistic_')]
class StatisticController extends BaseController
{
    public function __construct(
        private readonly Actions\UserTestGeneralAction $testGeneralAction,
        UserRepositoryInterface                        $repository

    )
    {
        parent::__construct($repository);
    }

    #[Route('', name: 'index', methods: ['GET', 'POST'])]
    public function index(Request $request): JsonResponse
    {
        $data = array_merge($request->query->all() ?? [], json_decode($request->getContent(), true) ?? []);

        $request = new UserTest(
//            пока не знаю, какие параметры понадобятся
//            \DateTimeImmutable::createFromFormat(DATE_ATOM, $from),
//            \DateTimeImmutable::createFromFormat(DATE_ATOM, $to),
            ...$this->getStats($this->getUser())

        );
        return $this->testGeneralAction->run($request);
    }
}