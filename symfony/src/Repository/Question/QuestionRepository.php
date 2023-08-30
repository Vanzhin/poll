<?php

namespace App\Repository\Question;

use App\Entity\Question;
use App\Entity\Section;
use App\Entity\Test;
use App\Repository\Interfaces\QuestionRepositoryInterface;
use App\Repository\Question\Filter\QuestionFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
class QuestionRepository extends ServiceEntityRepository implements QuestionRepositoryInterface
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


    public function getRandomPublishedByTest(Test $test, int $limit = 20): array
    {
        return $this->getOrCreateQueryBuilder()
            ->andWhere('qu.test = :testId')
            ->andWhere('qu.publishedAt IS NOT NULL')
            ->setParameters(['testId' => $test->getId()])
            ->setMaxResults($limit)
            ->addSelect('RAND() as HIDDEN rand')
            ->orderBy('rand')
            ->getQuery()
            ->getResult();
    }

    public function getAllIdAndSectionByTest(Test $test)
    {
        return $this->getOrCreateQueryBuilder()
            ->andWhere('qu.test = :testId')
            ->andWhere('qu.publishedAt IS NOT NULL')
            ->setParameters(['testId' => $test->getId()])
            ->join('qu.section', 'se')
            ->select(['se.id as section_id', 'JSON_ARRAYAGG(qu.id) as questions', 'count(qu.id) as count'])
            ->groupBy('se.id')
            ->getQuery()
            ->getArrayResult();


    }

    public function getAllPublishedByTest(Test $test)
    {
        return $this->getOrCreateQueryBuilder()
            ->andWhere('qu.test = :testId')
            ->andWhere('qu.publishedAt IS NOT NULL')
            ->setParameters(['testId' => $test])
            ->leftJoin('qu.section', 'se')
            ->addSelect('se')
            ->getQuery()
            ->getResult();


    }

    public function getByIdsSortBySection(array $ids)
    {
        return $this->getOrCreateQueryBuilder()
            ->leftJoin('qu.variant', 'va')
            ->addSelect('va')
            ->join('qu.type', 'ty')
            ->addSelect('ty')
            ->leftJoin('qu.tickets', 'ti')
            ->addSelect('ti')
            ->leftJoin('qu.section', 'se')
            ->addSelect('se')
            ->leftJoin('qu.subtitles', 'su')
            ->addSelect('su')
            ->andWhere('qu.id IN (:ids)')
            ->setParameters(['ids' => $ids])
            ->addOrderBy('se.title')
            ->getQuery()
            ->getResult();


    }

    public function getQuestionWithNoSection(Test $test): mixed
    {
        return $this->getOrCreateQueryBuilder()
            ->andWhere('qu.test = :test')
            ->andWhere('qu.section IS NULL')
            ->setParameters(['test' => $test])
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

    private function lastUnPublished(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder($queryBuilder)
            ->addOrderBy('qu.publishedAt', 'ASC')
            ->addOrderBy('qu.updatedAt', 'DESC');

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

    public function findLastUnPublishedByTestQuery(Test $test): QueryBuilder
    {
        return $this->lastUnPublished()
            ->leftJoin('qu.variant', 'va')
            ->addSelect('va')
            ->join('qu.type', 'ty')
            ->addSelect('ty')
            ->leftJoin('qu.tickets', 'ti')
            ->addSelect('ti')
            ->leftJoin('qu.section', 'se')
            ->addSelect('se')
            ->leftJoin('qu.subtitles', 'su')
            ->addSelect('su')
            ->andWhere('qu.test = :testId')
            ->setParameters(['testId' => $test->getId()]);
    }

    public function findAllByPublishedByTest(Test $test, bool $publish): mixed
    {
        $result = $this->getOrCreateQueryBuilder()
            ->addSelect('qu')
            ->andWhere('qu.test = :testId')
            ->setParameters(['testId' => $test]);

        if ($publish) {
            $result->andWhere('qu.publishedAt IS NULL');
        } else {
            $result->andWhere('qu.publishedAt IS NOT NULL');

        }
        return $result
            ->getQuery()
            ->getResult();
    }

    public function findLastUpdatedQuery(): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('qu');
        return
            $this->lastUpdated($queryBuilder);
    }

    public function findLastUpdatedBySectionQuery(Section $section): QueryBuilder
    {
        return $this->lastUpdated()->andWhere('qu.section = :sectionId')
            ->setParameters(['sectionId' => $section]);
    }

    public function buildFilter(QuestionFilter $filter): QueryBuilder
    {
        $query = $this->getOrCreateQueryBuilder();

        if ($filter->getTitle()) {
            $query->andWhere('qu.title LIKE :title')
                ->setParameter('title', "%{$filter->getTitle()}%");
        }
        if ($filter->getSection()) {
            $query->andWhere('qu.section = :section')
                ->setParameter('section', (int)$filter->getSection());
        }
        if ($filter->getTest()) {
            $query->andWhere('qu.test = :test')
                ->setParameter('test', (int)$filter->getTest());
        }
        if ($filter->getAuthor()) {
            $query->andWhere('qu.author = :author')
                ->setParameter('author', (int)$filter->getAuthor());
        }
        if (!is_null($filter->getIsPublished())) {
            if ($filter->getIsPublished()) {
                $query->andWhere('qu.publishedAt IS NOT NULL');
            } else {
                $query->andWhere('qu.publishedAt IS NULL');
            }
        }
        if ($filter->getDateInterval()) {
            if ($filter->hasDateIntervalFrom()) {
                $query->andWhere('qu.updatedAt >= :from')
                    ->setParameter('from', $filter->getDateInterval()->getFrom());
            }
            if ($filter->hasDateIntervalTo()) {
                $query->andWhere('qu.updatedAt <= :to')
                    ->setParameter('to', $filter->getDateInterval()->getTo());
            }
        }

        foreach ($filter->getSort() as $property => $direction) {
            $query->addOrderBy('qu.' . $property, $direction);
        }
        return $query;
    }
}
