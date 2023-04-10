<?php

namespace App\Factory\Organization;

use Doctrine\ORM\EntityManagerInterface;

class OrganizationFactory
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }
    public function createBuilder(): OrganizationBuilder
    {
        return new OrganizationBuilder($this->em);
    }

}