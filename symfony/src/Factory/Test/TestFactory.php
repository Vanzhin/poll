<?php

namespace App\Factory\Test;

use Doctrine\ORM\EntityManagerInterface;

class TestFactory
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }
    public function createBuilder(): TestBuilder
    {
        return new TestBuilder($this->em);
    }

}