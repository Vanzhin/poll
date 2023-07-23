<?php

namespace App\Repository\Interfaces;

use App\Entity\Company;
use App\Entity\User\User;
use App\Repository\User\Filter\UserFilter;

interface UserRepositoryInterface
{
    /**
     * @param UserFilter $filter
     * @return User[]
     */
    public function findAllWithFilter(UserFilter $filter, ?Company $company): array;

    public function findOneByLogin(string $login): ?User;

}