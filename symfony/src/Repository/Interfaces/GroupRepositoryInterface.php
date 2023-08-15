<?php

namespace App\Repository\Interfaces;

use App\Entity\Company;
use App\Entity\Group;
use App\Repository\Group\Filter\GroupFilter;
use Doctrine\ORM\QueryBuilder;

interface GroupRepositoryInterface
{
    public function buildFilter(GroupFilter $filter, Company $company = null): QueryBuilder;

    public function getById(int $group_id): ?Group;

}