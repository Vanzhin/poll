<?php

namespace App\Repository\Interfaces;

use App\Entity\Test;
use App\Repository\Test\Filter\TestFilter;

interface TestRepositoryInterface
{
    /**
     * @param TestFilter $filter
     * @return Test[]
     */
    public function findAllWithFilter(TestFilter $filter): array;

}