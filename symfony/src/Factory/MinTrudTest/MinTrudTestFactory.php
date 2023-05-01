<?php

namespace App\Factory\MinTrudTest;

use Doctrine\ORM\EntityManagerInterface;

class MinTrudTestFactory
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }
    public function createBuilder(): MinTrudTestBuilder
    {
        return new MinTrudTestBuilder($this->em);
    }

}