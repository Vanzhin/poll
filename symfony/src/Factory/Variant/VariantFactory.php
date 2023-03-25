<?php

namespace App\Factory\Variant;

use Doctrine\ORM\EntityManagerInterface;

class VariantFactory
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }
    public function createBuilder(): VariantBuilder
    {
        return new VariantBuilder($this->em);
    }

}