<?php

namespace App\Repository;

use App\Entity\Analytics\Analytics;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Analytics>
 *
 * @method Analytics|null find($id, $lockMode = null, $lockVersion = null)
 * @method Analytics|null findOneBy(array $criteria, array $orderBy = null)
 * @method Analytics[]    findAll()
 * @method Analytics[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnalyticsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Analytics::class);
    }

    public function save(Analytics $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Analytics $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('aly');
    }

    public function getAnalyticsByType(string $type)
    {
        return $this
            ->getOrCreateQueryBuilder()
            ->andWhere('aly.title LIKE :type')
            ->setParameters(['type' => '%' . mb_strtolower($type) . '%'])
            ->getQuery()
            ->getOneOrNullResult();


    }
}
