<?php

namespace App\Repository\User\Mapper;

use App\Repository\User\Filter\UserFilter;
use Symfony\Component\Validator\Constraints as Assert;

class UserFilterMapper
{
    public function buildFilter(array $data = []): UserFilter
    {
        $response = UserFilter::createDefault();
        $filter = $data['filter'] ?? [];

        if (isset($data['sort'])) {
            $response->setSort($data['sort']);
        }

        if (isset($filter['generalSearch'])) {
            $response->setGeneralSearch($filter['generalSearch']);
        }
        if (isset($filter['isActive'])) {
            $response->setIsActive($filter['isActive']);
        }

        if (isset($data['page'])) {
            $response->setPage($data['page']);
        }
        if (isset($data['limit'])) {
            $response->setLimit($data['limit']);
        }
//
        return $response;

    }

    public function getValidationCollection(): Assert\Collection
    {
        return new Assert\Collection([
            'filter' => new Assert\Collection(
            //пока будет по-простому
                [
                    'generalSearch' => new Assert\Optional([
                        new Assert\NotNull(),
                        new Assert\Type('string', message: 'test.title.format'),
                        new Assert\NotBlank(),
                        new Assert\Length(max: 255, maxMessage: 'profile.general.length')
                    ]),
                    'isActive' => new Assert\Optional([
                        new Assert\Type('boolean', message: 'test.title.format'),

                    ]),
                ]


//                [
//                    'firstName' => new Assert\Optional([
//                        new Assert\NotNull(),
//                        new Assert\Type('string', message: 'test.title.format'),
//                        new Assert\NotBlank(),
//                        new Assert\Length(max: 100, maxMessage: 'profile.first_name.length')
//                    ]),
//                    'middleName' => new Assert\Optional([
//                        new Assert\NotNull(),
//                        new Assert\Type('string', message: 'test.title.format'),
//                        new Assert\NotBlank(),
//                        new Assert\Length(max: 100, maxMessage: 'profile.last_name.length')
//                    ]),
//                    'lastName' => new Assert\Optional([
//                        new Assert\NotNull(),
//                        new Assert\Type('string', message: 'test.title.format'),
//                        new Assert\NotBlank(),
//                        new Assert\Length(max: 150, maxMessage: 'profile.first_name.length')
//                    ]),
//                    'position' => new Assert\Optional([
//                        new Assert\NotNull(),
//                        new Assert\Type('string', message: 'test.title.format'),
//                        new Assert\NotBlank(),
//                        new Assert\Length(max: 255, maxMessage: 'profile.position.length')
//                    ]),
//                    'department' => new Assert\Optional([
//                        new Assert\NotNull(),
//                        new Assert\Type('string', message: 'test.title.format'),
//                        new Assert\NotBlank(),
//                        new Assert\Length(max: 255, maxMessage: 'profile.department.length')
//                    ]),
//                    'snils' => new Assert\Optional([
//                        new Assert\NotNull(),
//                        new Assert\Regex(pattern: '/^\d{3}-\d{3}-\d{3} \d{2}$/', message: 'profile.snils.format')
//                    ]),
//                    'diploma' => new Assert\Optional([
//                        new Assert\NotNull(),
//                        new Assert\Type('string', message: 'test.title.format'),
//                        new Assert\NotBlank(),
//                        new Assert\Length(max: 255, maxMessage: 'profile.diploma.length')
//                    ]),
//                    'citizenship' => new Assert\Optional([
//                        new Assert\NotNull(),
//                        new Assert\Type('string', message: 'test.title.format'),
//                        new Assert\NotBlank(),
//                        new Assert\Length(max: 255, maxMessage: 'profile.citizenship.length')
//                    ]),
//                    'educationLevel' => new Assert\Optional([
//                        new Assert\Choice([
//                            'среднее профессиональное образование',
//                            'высшее образование - бакалавриат',
//                            'высшее образование - специалитет, магистратура',
//                            'высшее образование - подготовка кадров высшей квалификации'
//                        ],
//                            message: 'profile.education.choice')
//                    ]),
//                    'login' => new Assert\Optional([
//                        new Assert\NotNull(),
//                        new Assert\Type('string', message: 'test.title.format'),
//                        new Assert\NotBlank(),
//                        new Assert\Length(max: 255, maxMessage: 'profile.citizenship.length')
//                    ]),
//                    'isActive' => new Assert\Optional([
//                        new Assert\Type('boolean', message: 'test.title.format'),
//
//                    ]),
//
//                    'datetime' => new Assert\Optional([
//                        new Assert\Collection([
//                            'from' => new Assert\Optional([
//                                new Assert\NotNull(),
//                                new Assert\DateTime("Y-m-d H:i:s"),
//                                new Assert\NotBlank()
//                            ]),
//                            'to' => new Assert\Optional([
//                                new Assert\NotNull(),
//                                new Assert\DateTime("Y-m-d H:i:s"),
//                                new Assert\NotBlank()
//                            ])
//                        ])
//                    ]),
//                ]
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
        foreach (array_keys(UserFilter::$propertiesToSort) as $property) {
            $sort[$property] = $template;
        }
        return $sort;
    }
}