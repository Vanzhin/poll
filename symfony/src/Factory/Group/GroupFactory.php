<?php

namespace App\Factory\Group;

use App\Repository\Interfaces\UserRepositoryInterface;
use App\Repository\Test\TestRepository;
use Symfony\Component\Validator\Constraints as Assert;


class GroupFactory
{
    public function __construct(private readonly UserRepositoryInterface $userRepository, private readonly TestRepository $testRepository)
    {
    }

    public function createBuilder(): GroupBuilder
    {
        return new GroupBuilder($this->userRepository, $this->testRepository);
    }

    public function getValidationCollection(): Assert\Collection
    {
        return new Assert\Collection([
            'title' => new Assert\Optional([
                new Assert\NotNull(),
                new Assert\NotBlank(),
                new Assert\Type('string'),
                new Assert\Length(max: 255, maxMessage: 'group.title.length')])
            ,
            'participants' => new Assert\Optional([
                new Assert\Unique(),
                new Assert\All([
                    new Assert\Positive(),
                    new Assert\NotNull(),
                    new Assert\Type('string')
                ]),
            ]),
            'tests' => new Assert\Optional([
                new Assert\Unique(),
                new Assert\All([
                    new Assert\Positive(),
                    new Assert\NotNull(),
                    new Assert\Type('string')
                ]),
            ]),
            'started_at' => new Assert\Optional([
                new Assert\Positive(),
                new Assert\NotNull(),
                new Assert\DateTime('Y-m-d H:i:s')
            ]),
            'finished_at' => new Assert\Optional([
                new Assert\Positive(),
                new Assert\NotNull(),
                new Assert\DateTime('Y-m-d H:i:s')
            ])
        ]);
    }
}