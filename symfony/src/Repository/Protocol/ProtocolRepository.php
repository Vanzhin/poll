<?php

namespace App\Repository\Protocol;

use App\Entity\Company;
use App\Entity\Protocol\Protocol;
use App\Repository\Interfaces\ProtocolRepositoryInterface;
use App\Repository\Protocol\Filter\ProtocolFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Protocol>
 *
 * @method Protocol|null find($id, $lockMode = null, $lockVersion = null)
 * @method Protocol|null findOneBy(array $criteria, array $orderBy = null)
 * @method Protocol[]    findAll()
 * @method Protocol[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProtocolRepository extends ServiceEntityRepository implements ProtocolRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Protocol::class);
    }

    public function save(Protocol $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Protocol $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('pr');
    }

    public function buildFilter(ProtocolFilter $filter, Company $company = null): QueryBuilder
    {

        $query = $this->getOrCreateQueryBuilder();

        if ($filter->getNumber()) {
            $query->andWhere('pr.number = :number')
                ->setParameter('number', $filter->getNumber());
        }
        if ($filter->getGroup()) {
            $query->andWhere('pr.groups = :group')
                ->setParameter('group', $filter->getGroup());
        }
        if (!is_null($filter->getIsFile())) {
            if ($filter->getIsFile()) {
                $query->andWhere('pr.files IS NOT NULL');
            } else {
                $query->andWhere('pr.files IS NULL');
            }
        }
        if ($filter->getTest()) {
            $query->andWhere('pr.test = :test')
                ->setParameter('test', $filter->getTest());
        }
        if ($filter->getDateInterval()) {
            if ($filter->hasDateIntervalStarted()) {
                $query->andWhere('pr.createdAt >= :started_at')
                    ->setParameter('started_at', $filter->getDateInterval()->getFrom());
            }
            if ($filter->hasDateIntervalFinished()) {
                $query->andWhere('pr.createdAt <= :finished_at')
                    ->setParameter('finished_at', $filter->getDateInterval()->getTo());
            }
        }

        if ($company) {
            $query->join('pr.groups', 'gr')
                ->andWhere('gr.company = :company')
                ->setParameter('company', $company);
        }

        foreach ($filter->getSort() as $property => $direction) {
            $query->addOrderBy('pr.' . $property, $direction);
        }

        return $query;
    }
}
