<?php

namespace App\Repository\Company\Mapper;


use App\Repository\Company\Filter\CompanyFilter;
use Symfony\Component\Validator\Constraints as Assert;

class CompanyFilterMapper
{
    public function buildFilter(array $data = []): CompanyFilter
    {
        $response = CompanyFilter::createDefault();
        $filter = $data['filter'] ?? [];

        if (isset($data['sort'])) {
            $response->setSort($data['sort']);
        }

        if (isset($filter['title'])) {
            $response->setTitle($filter['title']);
        }

        if (isset($filter['datetime'])) {
            $response->setDateInterval(isset($filter['datetime']['from']) ? new \DateTimeImmutable($filter['datetime']['from']) : null,
                isset($filter['datetime']['to']) ? new \DateTimeImmutable($filter['datetime']['to']) : null);
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
                        new Assert\NotBlank()
                    ]),
                    'datetime' => new Assert\Optional([
                        new Assert\Collection([
                            'from' => new Assert\Optional([
                                new Assert\NotNull(),
                                new Assert\DateTime(DATE_ATOM),
                                new Assert\NotBlank()
                            ]),
                            'to' => new Assert\Optional([
                                new Assert\NotNull(),
                                new Assert\DateTime(DATE_ATOM),
                                new Assert\NotBlank()
                            ])
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
        $properties = ["id", "title", "createdAt", "updatedAt"];
        $template = new Assert\Optional([
            new Assert\NotNull(),
            new Assert\NotBlank(),
            new Assert\Choice(['ASC', 'DESC'], multiple: false)
        ]);
        $sort = [];
        foreach ($properties as $property) {
            $sort[$property] = $template;
        }
        return $sort;
    }
}