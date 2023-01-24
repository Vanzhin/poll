<?php

namespace App\Service;

use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class Paginator
{
    private PaginatorInterface $paginator;
    private RequestStack $requestStack;

    /**
     * @param PaginatorInterface $paginator
     */
    public function __construct(PaginatorInterface $paginator, RequestStack $requestStack)
    {
        $this->paginator = $paginator;
        $this->requestStack = $requestStack;
    }

    public function getPagination(QueryBuilder $query, int $limit, string $itemName = 'page', $default = 1): PaginationInterface
    {
        return $this->paginator->paginate(
            $query, /* query NOT result */
            $this->requestStack->getCurrentRequest()->query->getInt($itemName, $default)/*page number*/,
            $limit/*limit per page*/
        );

    }
}