<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Test;
use App\Interfaces\TestRepositoryInterface;
use App\Repository\Filter\TestFilter;
use App\Service\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Test>
 *
 * @method Test|null find($id, $lockMode = null, $lockVersion = null)
 * @method Test|null findOneBy(array $criteria, array $orderBy = null)
 * @method Test[]    findAll()
 * @method Test[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TestRepository extends ServiceEntityRepository implements TestRepositoryInterface
{
    public function __construct(ManagerRegistry $registry, Paginator $paginator)
    {
        parent::__construct($registry, Test::class);
    }

    public function save(Test $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Test $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findLastUpdatedQuery(): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('te');
        return
            $this->lastUpdated($queryBuilder);
    }

    private function latest(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder($queryBuilder)->orderBy('te.createdAt', 'DESC');

    }

    private function lastUpdated(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder($queryBuilder)->orderBy('te.updatedAt', 'DESC');

    }

    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('te');
    }

    public function findLastUpdatedByCategoryQuery(Category $category): QueryBuilder
    {
        return $this->findLastUpdatedQuery()
            ->andWhere('te.category = :categoryId')
            ->setParameters(['categoryId' => $category->getId()]);
    }

    public function getQuestionCount(Test $test): int
    {
        return $this->getOrCreateQueryBuilder()
            ->select('COUNT(qu.id)')
            ->join('te.question', 'qu')
            ->andWhere('te.id = :testId')
            ->setParameters(['testId' => $test->getId()])
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getSectionCount(Test $test): int
    {
        return $this->getOrCreateQueryBuilder()
            ->select('COUNT(se.id)')
            ->join('te.section', 'se')
            ->andWhere('te.id = :testId')
            ->setParameters(['testId' => $test->getId()])
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getTicketCount(Test $test): int
    {
        return $this->getOrCreateQueryBuilder()
            ->select('COUNT(ti.id)')
            ->join('te.ticket', 'ti')
            ->andWhere('te.id = :testId')
            ->setParameters(['testId' => $test->getId()])
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findAllWithFilter(TestFilter $filter): array
    {
        $query = $this->buildFilter($filter)
            ->setFirstResult($filter->getOffset())
            ->setMaxResults($filter->getLimit());

        return $query->getQuery()->getResult();

    }


    public function buildFilter(TestFilter $filter): QueryBuilder
    {
        $query = $this->getOrCreateQueryBuilder();
        if ($filter->getTitle()) {
            $query->andWhere('te.title LIKE :title')
                ->setParameter('title', "%{$filter->getTitle()}%");
        }
        if ($filter->getDescription()) {
            $query->andWhere('te.description LIKE :description')
                ->setParameter('description', "%{$filter->getDescription()}%");
        }
        if ($filter->getDateInterval()) {
            if ($filter->hasDateIntervalFrom()) {
                $query->andWhere('te.createdAt >= :from')
                    ->setParameter('from', $filter->getDateInterval()['from']->setTime(00, 00, 00));
            }
            if ($filter->hasDateIntervalTo()) {
                $query->andWhere('te.createdAt <= :to')
                    ->setParameter('to', $filter->getDateInterval()['to']->setTime(23, 59, 59));
            }

        }
        if ($filter->getMintrudTests()) {
            $query->andWhere('te.minTrudTest IN (:minTrudTest)')
                ->setParameter('minTrudTest', $filter->getMintrudTests());
        }
        if ($filter->getCategory()) {
            $query->andWhere('te.category = :category')
                ->setParameter('category', (int)$filter->getCategory());
        }

        return $query;
    }


}
