<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

final class CategoryRepository extends NestedTreeRepository
{
    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, $manager->getClassMetadata(Category::class));
    }

    public function findLastUpdatedQuery(): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('c');
        return
            $this->lastUpdated($queryBuilder);
    }

    private function latest(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder($queryBuilder)->orderBy('c.createdAt', 'DESC');

    }

    private function lastUpdated(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder($queryBuilder)->orderBy('c.updatedAt', 'DESC');

    }

    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('c');
    }

    public function findAllSortedByName(string $direction = 'ASC')
    {
        return $this->getOrCreateQueryBuilder()
            ->orderBy('c.title', $direction)
            ->getQuery()
            ->getResult();
    }

    public function findAllChildrenQuery(int $parentId = null): QueryBuilder
    {

        $queryBuilder = $this->getOrCreateQueryBuilder();
        if($parentId){
            return $queryBuilder
                ->andWhere('c.parent = :parentId')
                ->setParameters(['parentId'=> $parentId]);
        }else{
            return $queryBuilder
                ->andWhere('c.parent is NULL');
        }

    }
}
