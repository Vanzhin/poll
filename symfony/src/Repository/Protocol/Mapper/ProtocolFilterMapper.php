<?php

namespace App\Repository\Protocol\Mapper;

use App\Repository\Shared\Filter\vo\DateTimeInterval;
use App\Repository\Protocol\Filter\ProtocolFilter;
use Symfony\Component\Validator\Constraints as Assert;

class ProtocolFilterMapper
{
    public function buildFilter(array $data = []): ProtocolFilter
    {
        $response = ProtocolFilter::createDefault();
        $filter = $data['filter'] ?? [];

        if (isset($data['sort'])) {
            $response->setSort($data['sort']);
        }

        if (isset($filter['number'])) {
            $response->setNumber($filter['number']);
        }

        if (isset($filter['group'])) {
            $response->setGroup($filter['group']);
        }

        if (isset($filter['test'])) {
            $response->setTest($filter['test']);
        }

        if (isset($filter['file'])) {
            $response->setIsFile($filter['file']);
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
                    'number' => new Assert\Optional([
                        new Assert\NotNull(),
                        new Assert\Type('string'),
                        new Assert\NotBlank(),
                        new Assert\Length(max: 10, maxMessage: 'protocol.number.max')
                    ]),

                    'group' => new Assert\Optional([
                        new Assert\NotNull(),
                        new Assert\Type('digit'),
                        new Assert\NotBlank(),
                    ]),
                    'test' => new Assert\Optional([
                        new Assert\NotNull(),
                        new Assert\Type('digit'),
                        new Assert\NotBlank(),
                    ]),
                    'file' => new Assert\Optional([
                        new Assert\NotNull(),
                        new Assert\Type('boolean'),
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
        foreach (ProtocolFilter::$propertiesToSort as $property) {
            $sort[$property] = $template;
        }
        return $sort;
    }
}