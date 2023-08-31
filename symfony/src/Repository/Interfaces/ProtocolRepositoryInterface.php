<?php

namespace App\Repository\Interfaces;

use App\Entity\Company;
use App\Repository\Protocol\Filter\ProtocolFilter;
use Doctrine\ORM\QueryBuilder;

interface ProtocolRepositoryInterface
{
    public function buildFilter(ProtocolFilter $filter, Company $company = null): QueryBuilder;

}