<?php

namespace App\Repository;

use App\Entity\Question;
use App\Entity\Test;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\ParameterType;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Question>
 *
 * @method Question|null find($id, $lockMode = null, $lockVersion = null)
 * @method Question|null findOneBy(array $criteria, array $orderBy = null)
 * @method Question[]    findAll()
 * @method Question[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Question::class);
    }

    public function save(Question $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Question $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function getRandomQByTest(Test $test, int $limit = 20): array
    {
        return $this->getOrCreateQueryBuilder()
            ->andWhere('qu.test = :testId')
            ->setParameters(['testId'=>$test->getId()])
            ->setMaxResults($limit)
            ->addSelect('RAND() as HIDDEN rand')
            ->orderBy('rand')
            ->getQuery()
            ->getResult();
    }

    private function latest(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder($queryBuilder)->orderBy('qu.createdAt', 'DESC');

    }

    private function lastUpdated(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder($queryBuilder)->orderBy('qu.updatedAt', 'DESC');

    }

    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('qu');
    }

    public function findLastUpdatedByTestQuery(Test $test): QueryBuilder
    {
        return $this->lastUpdated()->andWhere('qu.test = :testId')
            ->setParameters(['testId' => $test->getId()]);
    }

    public function findLastUpdatedQuery(): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('qu');
        return
            $this->lastUpdated($queryBuilder);
    }

//    /**
//     * @return Question[] Returns an array of Question objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('q.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Question
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
