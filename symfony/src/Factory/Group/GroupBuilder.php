<?php

declare(strict_types=1);

namespace App\Factory\Group;

use App\Entity\Group;
use App\Entity\Test;
use App\Entity\User\User;
use App\Repository\Interfaces\UserRepositoryInterface;
use App\Repository\Test\TestRepository;
use App\Response\AppException;

class GroupBuilder
{
    public function __construct(private readonly UserRepositoryInterface $userRepository, private readonly TestRepository $testRepository)
    {
    }

    public function build(array $data, User $owner = null, Group $group = null): Group
    {
        if (!$group) {
            $group = new Group();
        }
        if ($owner) {
            $group->setCompany($owner->getCompany());
            $group->setOwner($owner);
        }

        foreach ($data as $key => $item) {
            if ($key === 'title') {
                $group->setTitle($item);
                continue;
            };
            if ($key === 'participants') {
                $group->removeAllParticipants();
                $userIds = array_map(fn($id) => ((int)$id), $item);
                $users = $this->userRepository->findCompanyUsersById($group->getCompany(), ...$userIds);
                foreach ($userIds as $userId) {
                    if (!$participant = current(array_filter($users, fn(User $user) => ($user->getId() === $userId)))) {
                        throw new AppException(sprintf('Пользователь с идентификатором \'%d\' не найден', $userId));
                    }
                    $group->addParticipant($participant);
                }
                continue;
            };

            if ($key === 'tests') {
                $group->removeAllAvailableTest();
                $testIds = array_map(fn($id) => ((int)$id), $item);
                $tests = $this->testRepository->findBy(['id' => [...$testIds]]);
                foreach ($testIds as $testId) {
                    if (!$test = current(array_filter($tests, fn(Test $test) => ($test->getId() === $testId)))) {
                        throw new AppException(sprintf('Тест с идентификатором \'%d\' не найден.', $testId));
                    }
                    $group->addAvailableTest($test);
                }
                continue;
            }

            if ($key === 'started_at') {
                $group->setStartedAt(new \DateTimeImmutable($item));
                continue;
            }
            if ($key === 'finished_at') {
                $group->setFinishedAt((new \DateTimeImmutable($item)));
                continue;
            }
        }
        if ($group->getStartedAt() > $group->getFinishedAt()) {
            throw new AppException('Дата окончания обучения в группе не может быть раньше, чем дата начала.');

        }
        if ($group->getStartedAt() < new \DateTimeImmutable()) {
            throw new AppException('Дата начала обучения не может быть в прошлом.');

        }
        return $group;
    }
}