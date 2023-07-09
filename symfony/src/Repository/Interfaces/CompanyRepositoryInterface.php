<?php

namespace App\Repository\Interfaces;

use App\Entity\Company;
use App\Repository\Company\Filter\CompanyFilter;

interface CompanyRepositoryInterface
{
    /**
     * @param CompanyFilter $filter
     * @return Company[]
     */
    public function findAllWithFilter(CompanyFilter $filter): array;

}