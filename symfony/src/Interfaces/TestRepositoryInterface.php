<?php

namespace App\Interfaces;

use App\Entity\Test;
use App\Repository\Filter\TestFilter;

interface TestRepositoryInterface
{
    /**
     * @param TestFilter $filter
     * @return Test[]
     */
    public function findAllWithFilter(TestFilter $filter): array;

}