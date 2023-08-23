<?php

namespace App\Factory\Company;

use Doctrine\ORM\EntityManagerInterface;

class CompanyFactory
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }
    public function createBuilder(): CompanyBuilder
    {
        return new CompanyBuilder($this->em);
    }

}