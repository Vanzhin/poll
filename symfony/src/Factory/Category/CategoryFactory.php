<?php

namespace App\Factory\Category;

use Doctrine\ORM\EntityManagerInterface;

class CategoryFactory
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }
    public function createBuilder(): CategoryBuilder
    {
        return new CategoryBuilder($this->em);
    }

}