<?php

namespace App\Factory\Section;

use Doctrine\ORM\EntityManagerInterface;

class SectionFactory
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }
    public function createBuilder(): SectionBuilder
    {
        return new SectionBuilder($this->em);
    }

}