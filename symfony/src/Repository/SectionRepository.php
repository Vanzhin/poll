<?php

namespace App\Repository;

use App\Entity\Section;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Section>
 *
 * @method Section|null find($id, $lockMode = null, $lockVersion = null)
 * @method Section|null findOneBy(array $criteria, array $orderBy = null)
 * @method Section[]    findAll()
 * @method Section[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Section::class);
    }

    public function save(Section $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Section $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    private function findAllQuery(): QueryBuilder
    {
        $queryBuilder = $this->getOrCreateQueryBuilder();
        return
            $queryBuilder
                ->leftJoin('s.test', 't')
                ->addSelect('t');
    }

    public function findAllSortedByTitleQuery(string $direction = 'ASC'): QueryBuilder
    {
        return $this->findAllQuery()->orderBy('s.title', $direction);
    }

    public function findLatestQuery(string $direction = 'DESC'): QueryBuilder
    {
        return $this->findAllQuery()->orderBy('s.updatedAt', $direction);
    }


    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('s');
    }
    public function findAllSortedByName(string $direction = 'ASC')
    {
        return $this->getOrCreateQueryBuilder()
            ->orderBy('s.title', $direction)
            ->getQuery()
            ->getResult();
    }

}
