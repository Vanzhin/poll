<?php

namespace App\Factory\Result;

use Doctrine\ORM\EntityManagerInterface;

class ResultFactory
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }
    public function createBuilder(): ResultBuilder
    {
        return new ResultBuilder($this->em);
    }

}