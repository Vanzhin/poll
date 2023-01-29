<?php

namespace App\Repository;

use App\Entity\Question;
use App\Entity\Test;
use App\Entity\Ticket;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\ParameterType;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\ResultSetMapping;


/**
 * @extends ServiceEntityRepository<Test>
 *
 * @method Test|null find($id, $lockMode = null, $lockVersion = null)
 * @method Test|null findOneBy(array $criteria, array $orderBy = null)
 * @method Test[]    findAll()
 * @method Test[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
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

    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('t');
    }

    /**
     * @throws Exception
     */
    public function getRandomQuestions(Test $test, int $limit = 20): array
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        $sql = "SELECT q.id FROM test t JOIN test_ticket tt on t.id = tt.test_id JOIN ticket_question tq on tt.ticket_id = tq.ticket_id JOIN question q on q.id = tq.question_id WHERE t.id = :testId ORDER BY RAND() LIMIT :limit";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':testId', $test->getId(),ParameterType::INTEGER);
        $stmt->bindValue(':limit', $limit, ParameterType::INTEGER);
        $raw = $stmt->executeQuery()->fetchFirstColumn();
        return $this->getEntityManager()->getRepository(Question::class)->findBy(['id'=> $raw]);
    }

    public function findLatsUpdatedQuery(): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('t');
        return
            $this->lastUpdated($queryBuilder)
                ->leftJoin('t.ticket', 'ti')
                ->addSelect('ti');
    }

    private function latest(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder($queryBuilder)->orderBy('t.createdAt', 'DESC');

    }

    private function lastUpdated(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder($queryBuilder)->orderBy('t.updatedAt', 'DESC');

    }

}
