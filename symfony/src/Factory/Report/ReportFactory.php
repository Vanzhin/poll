<?php

namespace App\Factory\Report;

use Doctrine\ORM\EntityManagerInterface;

class ReportFactory
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }
    public function createBuilder(): ReportBuilder
    {
        return new ReportBuilder($this->em);
    }

}