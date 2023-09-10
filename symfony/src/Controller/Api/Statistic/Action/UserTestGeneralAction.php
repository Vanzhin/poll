<?php

namespace App\Controller\Api\Statistic\Action;

use App\Controller\Api\BaseAction\NewBaseAction;
use App\Entity\Test;
use App\Entity\User\User;
use App\Repository\Interfaces\UserRepositoryInterface;
use App\Repository\Test\Filter\UserTest;
use App\Service\Paginator;
use App\Service\SerializerService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserTestGeneralAction extends NewBaseAction
{
    public function __construct(
        private readonly UserRepositoryInterface $repository,
        private readonly Paginator               $paginator,
        SerializerService                        $serializer)
    {
        parent::__construct($serializer);
    }

    public function run(UserTest $request): JsonResponse
    {
        $statistic = [];

        $pagination = $this->paginator->getPagination($this->repository->getGeneralStatistic($request));

        if ($pagination->count() <= 0) {
            throw new NotFoundHttpException();
        }
        /** @var User[] $users */
        foreach ($pagination as $user) {
            $expired = [];
            $passed = [];
            $assigned = [];
            foreach ($user->getResults() as $result) {
                $passed[] = $result->getTest();
            }
            foreach ($user->getGroups() as $group) {
                $assigned[] = $group->getAvailableTests();
                if ($group->getFinishedAt() < new \DateTimeImmutable() && $group->getAvailableTests()->count() > 0) {
                    $expired[] = $group->getAvailableTests()->filter(function (Test $test) use ($passed) {
                        return !in_array($test, $passed);
                    });
                }
            }
            $statistic[$user->getId()]['passed'] = $passed;
            $statistic[$user->getId()]['assigned'] = $assigned;
            $statistic[$user->getId()]['expired'] = $expired;
        }

        return $this->successResponse([
            "statistic" => $statistic,
            "pagination" => $this->paginator->getInfo($pagination)
        ], ['test_statistic']);
    }
}