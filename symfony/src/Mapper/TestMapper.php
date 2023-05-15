<?php

namespace App\Mapper;


use App\Repository\Filter\TestFilter;
use Symfony\Component\Validator\Constraints as Assert;

class TestMapper
{
    public function buildTestFilter(array $data = []): TestFilter
    {
        $response = TestFilter::createDefault();
        $filter = $data['filter'] ?? [];

        if (isset($data['sort'])) {
            $response->setSort($data['sort']);
        }

        if (isset($filter['title'])) {
            $response->setTitle($filter['title']);
        }
        if (isset($filter['description'])) {
            $response->setDescription($filter['description']);
        }
        if (isset($filter['datetime'])) {
            $response->setDateInterval(isset($filter['datetime']['from']) ? new \DateTimeImmutable($filter['datetime']['from']) : null,
                isset($filter['datetime']['to']) ? new \DateTimeImmutable($filter['datetime']['to']) : null);
        }
        if (isset($filter['mintrud_tests'])) {
            $response->setMintrudTests(...$filter['mintrud_tests']);
        }
        if (isset($filter['category'])) {
            $response->setCategory($filter['category']);
        }

        if (isset($data['page'])) {
            $response->setPage($data['page']);
        }
        if (isset($data['limit'])) {
            $response->setLimit($data['limit']);
        }

        return $response;

    }

    public function getValidationCollectionTestList(): Assert\Collection
    {
        return new Assert\Collection([
            'filter' => new Assert\Collection(
                [
                    'title' => new Assert\Optional([
                        new Assert\NotNull(),
                        new Assert\Type('string', message: 'test.title.format'),
                        new Assert\NotBlank()
                    ]),
                    'description' => new Assert\Optional([
                        new Assert\NotNull(),
                        new Assert\Type('string', message: 'test.description.format'),
                        new Assert\NotBlank()
                    ]),
                    'category' => new Assert\Optional([
                        new Assert\NotNull(),
                        new Assert\Type('string', message: 'test.category.format'),
                        new Assert\NotBlank()
                    ]),
                    'datetime' => new Assert\Optional([
                        new Assert\Collection([
                            'from' => new Assert\Optional([
                                new Assert\NotNull(),
                                new Assert\DateTime('Y-m-d'),
                                new Assert\NotBlank()
                            ]),
                            'to' => new Assert\Optional([
                                new Assert\NotNull(),
                                new Assert\DateTime('Y-m-d'),
                                new Assert\NotBlank()
                            ])
                        ])


                    ]),
                    'mintrud_tests' => new Assert\Optional(
                        [
                            new Assert\NotNull(),
                            new Assert\Type('array'),
                            new Assert\All([
                                new Assert\NotBlank(),
                                new Assert\Type('string')
                            ])
                        ],
                    ),

                ]
            ),
            'sort' => new Assert\Optional([
                new Assert\NotNull(),
                new Assert\NotBlank(),
                new Assert\Collection($this->getSort()),
            ]),
        ],
            allowExtraFields: true,
        );
    }

    private function getSort(): array
    {
        //выбираю доступные поля из Test::class, делаю возможность выполнять сортировку только по ним
// перенести в Test::class? типа Test::getSort();
//        $properties = array_keys((new \ReflectionClass(Test::class))->getDefaultProperties());
        $properties = ["id", "title", "description", "category", "minTrudTest", "time", "createdAt", "updatedAt"];
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