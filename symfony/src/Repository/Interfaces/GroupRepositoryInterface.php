<?php

namespace App\Repository\Interfaces;

use App\Entity\Company;
use App\Repository\Group\Filter\GroupFilter;
use Doctrine\ORM\QueryBuilder;

interface GroupRepositoryInterface
{
    public function buildFilter(GroupFilter $filter, Company $company = null): QueryBuilder;
}