<?php

namespace App\Factory\Commission;

use App\Repository\Interfaces\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraints as Assert;


class CommissionFactory
{
    public function __construct(private readonly UserRepositoryInterface $repository)
    {
    }

    public function createBuilder(): CommissionBuilder
    {
        return new CommissionBuilder($this->repository);
    }

//    public function getValidationCollection(): Assert\Collection
//    {
//        return new Assert\Collection([
//            'title' => [
//                new Assert\NotNull(),
//                new Assert\NotBlank(),
//                new Assert\Type('string'),
//                new Assert\Length(max: 255, maxMessage: 'commission'
//                )
//
//            ],new Assert\Optional([
//                new Assert\NotNull(),
//                new Assert\NotBlank(),
//                new Assert\Collection($this->getSort()),
//            ]),
//            'page' => new Assert\Optional([
//                new Assert\Positive(),
//                new Assert\NotNull(),
//                new Assert\Type('digit')
//            ]),
//            'limit' => new Assert\Optional([
//                new Assert\Positive(),
//                new Assert\NotNull(),
//                new Assert\Type('digit')
//
//            ]),
//        ]);
//    }

}