<?php

namespace App\Repository\Question\Mapper;

use App\Repository\Group\Filter\GroupFilter;
use App\Repository\Group\Filter\vo\DateTimeInterval;
use App\Repository\Question\Filter\QuestionFilter;
use Symfony\Component\Validator\Constraints as Assert;

class QuestionFilterMapper
{
    public function buildFilter(array $data = []): QuestionFilter
    {
        $response = QuestionFilter::createDefault();
        $filter = $data['filter'] ?? [];

        if (isset($data['sort'])) {
            $response->setSort($data['sort']);
        }

        if (isset($filter['title'])) {
            $response->setTitle($filter['title']);
        }

        if (isset($filter['section'])) {
            $response->setSection($filter['section']);
        }
        if (isset($filter['author'])) {
            $response->setAuthor($filter['author']);
        }
        if (isset($filter['test'])) {
            $response->setTest($filter['test']);
        }
        if (isset($filter['is_published'])) {
            $response->setIsPublished($filter['is_published']);
        }
        if (isset($filter['date'])) {
            $response->setDateInterval(new DateTimeInterval($filter['date']['from'] ?? null, $filter['date']['to'] ?? null));
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
                        new Assert\Type('string'),
                        new Assert\NotBlank(),
                        new Assert\Length(max: 255)
                    ]),

                    'section' => new Assert\Optional([
                        new Assert\NotNull(),
                        new Assert\Type('string'),
                        new Assert\NotBlank(),
                    ]),
                    'author' => new Assert\Optional([
                        new Assert\NotNull(),
                        new Assert\Type('string'),
                        new Assert\NotBlank(),
                    ]),
                    'test' => new Assert\Optional([
                        new Assert\NotNull(),
                        new Assert\Type('string'),
                        new Assert\NotBlank(),
                    ]),
                    'is_published' => new Assert\Optional([
                        new Assert\NotNull(),
                        new Assert\Type('boolean'),
                    ]),
                    'date' => new Assert\Optional([
                        new Assert\Collection([
                            'from' => new Assert\Optional([
                                new Assert\Positive(),
                                new Assert\NotNull(),
                                new Assert\DateTime('Y-m-d H:i:s')
                            ]),
                            'to' => new Assert\Optional([
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