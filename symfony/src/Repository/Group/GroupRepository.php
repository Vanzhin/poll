<?php

namespace App\Repository\Group;

use App\Entity\Company;
use App\Entity\Group;
use App\Repository\Group\Filter\GroupFilter;
use App\Repository\Interfaces\GroupRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Group>
 *
 * @method Group|null find($id, $lockMode = null, $lockVersion = null)
 * @method Group|null findOneBy(array $criteria, array $orderBy = null)
 * @method Group[]    findAll()
 * @method Group[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupRepository extends ServiceEntityRepository implements GroupRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Group::class);
    }

    public function save(Group $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('gr');
    }

    public function remove(Group $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function buildFilter(GroupFilter $filter, Company $company = null): QueryBuilder
    {
        $query = $this->getOrCreateQueryBuilder();

        if ($filter->getTitle()) {
            $query->andWhere('gr.title LIKE :title')
                ->setParameter('title', "%{$filter->getTitle()}%");
        }
        if ($filter->getOwner()) {
            $query->andWhere('gr.owner = :owner')
                ->setParameter('owner', $filter->getOwner());
        }
        if ($filter->getDateInterval()) {
            if ($filter->hasDateIntervalStarted()) {
                $query->andWhere('gr.started_at >= :started_at')
                    ->setParameter('started_at', $filter->getDateInterval()->getFrom());
            }
            if ($filter->hasDateIntervalFinished()) {
                $query->andWhere('gr.finished_at <= :finished_at')
                    ->setParameter('finished_at', $filter->getDateInterval()->getTo());
            }
        }

        if ($company) {
            $query->andWhere('gr.company = :company')
                ->setParameter('company', $company);
        }

        foreach ($filter->getSort() as $property => $direction) {
            $query->addOrderBy('gr.' . $property, $direction);
        }

        return $query;
    }
}