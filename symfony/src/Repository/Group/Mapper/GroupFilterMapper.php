<?php

namespace App\Repository\Group\Mapper;

use App\Repository\Group\Filter\GroupFilter;
use App\Repository\Shared\Filter\vo\DateTimeInterval;
use Symfony\Component\Validator\Constraints as Assert;

class GroupFilterMapper
{
    public function buildFilter(array $data = []): GroupFilter
    {
        $response = GroupFilter::createDefault();
        $filter = $data['filter'] ?? [];

        if (isset($data['sort'])) {
            $response->setSort($data['sort']);
        }

        if (isset($filter['title'])) {
            $response->setTitle($filter['title']);
        }

        if (isset($filter['owner'])) {
            $response->setOwner($filter['owner']);
        }

        if (isset($filter['date'])) {
            $response->setDateInterval(new DateTimeInterval($filter['date']['started_at'] ?? null, $filter['date']['finished_at'] ?? null));
        }

        if (isset($data['page'])) {
            $response->setPage($data['page']);
        }
        if (isset($data['limit'])) {
            $response->setLimit($data['limit']);
        }

        return $response;
    }

    public function getValidationCollection(): Assert\Collection
    {
        return new Assert\Collection([
            'filter' => new Assert\Collection(
                [
                    'title' => new Assert\Optional([
                        new Assert\NotNull(),
                        new Assert\Type('string', message: 'test.title.format'),
                        new Assert\NotBlank(),
                        new Assert\Length(max: 255, maxMessage: 'profile.general.length')
                    ]),

                    'owner' => new Assert\Optional([
                        new Assert\NotNull(),
                        new Assert\Type('string'),
                        new Assert\NotBlank(),
                    ]),
                    'date' => new Assert\Optional([
                        new Assert\Collection([
                            'started_at' => new Assert\Optional([
                                new Assert\Positive(),
                                new Assert\NotNull(),
                                new Assert\DateTime('Y-m-d H:i:s')
                            ]),
                            'finished_at' => new Assert\Optional([
                                new Assert\Positive(),
                                new Assert\NotNull(),
                                new Assert\DateTime('Y-m-d H:i:s')
                            ]),
                        ])
                    ]),
                ]
            ),
            'sort' => new Assert\Optional([
                new Assert\NotNull(),
                new Assert\NotBlank(),
                new Assert\Collection($this->getSort()),
            ]),
            'page' => new Assert\Optional([
                new Assert\Positive(),
                new Assert\NotNull(),
                new Assert\Type('digit')
            ]),
            'limit' => new Assert\Optional([
                new Assert\Positive(),
                new Assert\NotNull(),
                new Assert\Type('digit')
            ]),
        ]);
    }

    private function getSort(): array
    {
        $template = new Assert\Optional([
            new Assert\NotNull(),
            new Assert\NotBlank(),
            new Assert\Choice(['ASC', 'DESC'], multiple: false)
        ]);
        $sort = [];
        foreach (GroupFilter::$propertiesToSort as $property) {
            $sort[$property] = $template;
        }
        return $sort;
    }
}