<?php

namespace App\Repository\User;

use App\Entity\Company;
use App\Entity\User\User;
use App\Repository\Interfaces\UserRepositoryInterface;
use App\Repository\User\Filter\UserFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface, UserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->save($user, true);
    }

    public function findLastUpdatedQuery(): QueryBuilder
    {
        $queryBuilder = $this->getOrCreateQueryBuilder();
        return
            $this->lastUpdated($queryBuilder)
                ->leftJoin('u.results', 'r')
                ->addSelect('r');
    }

    private function latest(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder($queryBuilder)->orderBy('u.createdAt', 'DESC');

    }

    private function lastUpdated(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder($queryBuilder)->orderBy('u.updatedAt', 'DESC');

    }

    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('u');
    }

    public function findLatestQuery(): QueryBuilder
    {
        return $this->latest();
    }

    public function findAllWithFilter(UserFilter $filter, ?Company $company): array
    {
        $query = $this->buildFilter($filter, $company)
            ->setFirstResult($filter->getOffset())
            ->setMaxResults($filter->getLimit());

        return $query->getQuery()->getResult();
    }

    public function buildFilter(UserFilter $filter, ?Company $company): QueryBuilder
    {
        $query = $this->getOrCreateQueryBuilder()->leftJoin('u.profile', 'pr');

        if ($filter->getGeneralSearch()) {
            $query->andWhere(
                'pr.firstName LIKE :title OR pr.lastName LIKE :title OR pr.middleName LIKE :title OR u.login LIKE :title')
                ->setParameter('title', "%{$filter->getGeneralSearch()}%");
        }
        if (!is_null($filter->getIsActive())) {
            $query->andWhere('u.isActive = :isActive')
                ->setParameter('isActive', $filter->getIsActive());
        }

        if ($company) {
            $query->andWhere('u.company = :company')
                ->setParameter('company', $company);
        }
        foreach ($filter->getSort() as $property => $direction) {
            $query->addOrderBy(UserFilter::$propertiesToSort[$property] . '.' . $property, $direction);
        }
        return $query;
    }

    public function findOneByLogin(string $login): ?User
    {
        return $this->getOrCreateQueryBuilder()
            ->leftJoin('u.profile', 'pr')
            ->addSelect('pr')
            ->andWhere('u.login = :login')
            ->setParameter('login', $login)
            ->getQuery()->getOneOrNullResult();
    }

    /**
     * @param string $email
     * @return User[]
     */
    public function findAllByEmail(string $email): array
    {
        return $this->getOrCreateQueryBuilder()
            ->leftJoin('u.profile', 'pr')
            ->addSelect('pr')
            ->andWhere('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery()->getResult();
    }

    /**
     * @param int ...$userIds
     * @return User[]
     */
    public function findAllById(int ...$userIds): array
    {
        return $this->getOrCreateQueryBuilder()
            ->leftJoin('u.profile', 'pr')
            ->addSelect('pr')
            ->andWhere('u.id IN (:userIds)')
            ->setParameter('userIds', $userIds)
            ->getQuery()->getResult();
    }
}
