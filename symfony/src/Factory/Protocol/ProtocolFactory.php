<?php

namespace App\Factory\Protocol;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraints as Assert;


class ProtocolFactory
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function createBuilder(): ProtocolBuilder
    {
        return new ProtocolBuilder($this->em);
    }

    public function getValidationCollection(): Assert\Collection
    {
        return new Assert\Collection([
            'order_number' => new Assert\Optional([
                new Assert\NotNull(),
                new Assert\NotBlank(),
                new Assert\Type('string'),
                new Assert\Length(max: 10)])
            ,
            'commission_id' => new Assert\Optional([
                new Assert\NotNull(),
                new Assert\NotBlank(),
                new Assert\Type('string'),
                new Assert\Length(max: 10)])
            ,
            'group_id' => new Assert\Optional([
                new Assert\NotNull(),
                new Assert\NotBlank(),
                new Assert\Type('string'),
                new Assert\Length(max: 10)])
            ,
            'order_date' => new Assert\Optional([
                new Assert\NotNull(),
                new Assert\DateTime('Y-m-d')
            ]),
            'check_reason' => new Assert\Optional([
                new Assert\NotNull(),
                new Assert\NotBlank(),
                new Assert\Type('string'),
            ])
        ]);
    }
}