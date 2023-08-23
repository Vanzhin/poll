<?php

namespace App\Repository\Company;

use App\Entity\Company;
use App\Repository\Company\Filter\CompanyFilter;
use App\Repository\Interfaces\CompanyRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Company>
 *
 * @method Company|null find($id, $lockMode = null, $lockVersion = null)
 * @method Company|null findOneBy(array $criteria, array $orderBy = null)
 * @method Company[]    findAll()
 * @method Company[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompanyRepository extends ServiceEntityRepository implements CompanyRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Company::class);
    }

    public function save(Company $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Company $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllWithFilter(CompanyFilter $filter): array
    {
        $query = $this->buildFilter($filter)
            ->setFirstResult($filter->getOffset())
            ->setMaxResults($filter->getLimit());

        return $query->getQuery()->getResult();

    }

    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('co');
    }

    public function buildFilter(CompanyFilter $filter): QueryBuilder
    {
        $query = $this->getOrCreateQueryBuilder();
        if ($filter->getTitle()) {
            $query->andWhere('co.title LIKE :title')
                ->setParameter('title', "%{$filter->getTitle()}%");
        }

        if ($filter->getDateInterval()) {
            if ($filter->hasDateIntervalFrom()) {
                $query->andWhere('co.createdAt >= :from')
                    ->setParameter('from', $filter->getDateInterval()['from']);
            }
            if ($filter->hasDateIntervalTo()) {
                $query->andWhere('co.createdAt <= :to')
                    ->setParameter('to', $filter->getDateInterval()['to']);
            }
        }

        foreach ($filter->getSort() as $property => $direction) {
            $query->addOrderBy('co.' . $property, $direction);
        }

        return $query;
    }
}
