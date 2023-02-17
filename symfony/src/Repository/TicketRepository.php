<?php

namespace App\Repository;

use App\Entity\Question;
use App\Entity\Section;
use App\Entity\Test;
use App\Entity\Ticket;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\ParameterType;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ticket>
 *
 * @method Ticket|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ticket|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ticket[]    findAll()
 * @method Ticket[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TicketRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ticket::class);
    }

    public function save(Ticket $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Ticket $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllBySection(Section $section = null): mixed
    {
        $tests = $this->getOrCreateQueryBuilder()
            ->join('ti.tests', 't','right')
            ->join('t.section', 's');

        if ($section){
            $tests->andWhere('t.section = :section')
                ->setParameter('section', $section);
        }

        return  $tests
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @throws Exception
//     */
//    public function findAllBySection(Section $section = null): array
//    {
//        $conn = $this->getEntityManager()
//            ->getConnection();
//        $sql = "SELECT t2.id FROM section s JOIN test t on s.id = t.section_id JOIN test_ticket tt on t.id = tt.test_id JOIN ticket t2 on t2.id = tt.ticket_id";
//        if ($section) {
//            $sql .= " WHERE s.id = :sectionId";
//        }
//        $sql .= " ORDER BY s.id";
//
//
//        $stmt = $conn->prepare($sql);
//
//        if ($section) {
//            $stmt->bindValue(':sectionId', $section->getId(), ParameterType::INTEGER);
//        }
//
//        $raw = $stmt->executeQuery()->fetchFirstColumn();
//        return $this->getEntityManager()->getRepository(Ticket::class)->findBy(['id' => $raw]);
//    }

    public function findLastUpdatedQuery(): QueryBuilder
    {
        $queryBuilder = $this->getOrCreateQueryBuilder();
        return
            $this->lastUpdated($queryBuilder)
                ->leftJoin('ti.question', 'qu')
                ->addSelect('qu');
    }

    private function latest(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder($queryBuilder)->orderBy('ti.createdAt', 'DESC');

    }

    private function lastUpdated(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder($queryBuilder)->orderBy('ti.updatedAt', 'DESC');

    }

    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('ti');
    }


//    /**
//     * @return Ticket[] Returns an array of Ticket objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Ticket
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
