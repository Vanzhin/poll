<?php

namespace App\Repository\Interfaces;

use App\Entity\Company;
use App\Entity\Group;
use App\Entity\User\User;
use App\Repository\Test\Filter\UserTest;
use App\Repository\User\Filter\UserFilter;
use Doctrine\ORM\QueryBuilder;

interface UserRepositoryInterface
{
    /**
     * @param UserFilter $filter
     * @return User[]
     */
    public function findAllWithFilter(UserFilter $filter, ?Company $company): array;

    public function findOneByLogin(string $login): ?User;

    public function findAllByEmail(string $email): array;

    public function findAllById(int ...$user_id): array;

    public function findCompanyUsersById(Company $company, int ...$userIds): array;

    public function findGroupOwnerToChange(Group $group, array $roles): array;

    public function findUsersByCompany(Company $company): array;

    public function getGeneralStatistic(UserTest $request);
    public function buildFilter(UserFilter $filter, ?Company $company): QueryBuilder;


}