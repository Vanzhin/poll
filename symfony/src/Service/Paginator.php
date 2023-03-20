<?php

namespace App\Service;

use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class Paginator
{
    const LIMIT_PER_PAGE = 5;

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

    public function getPagination(QueryBuilder $query, string $itemName = 'page', $default = 1): PaginationInterface
    {
        $limit = $this->requestStack->getCurrentRequest()->get('limit', static::LIMIT_PER_PAGE);
        return $this->paginator->paginate(
            $query, /* query NOT result */
            $this->requestStack->getCurrentRequest()->query->getInt($itemName, $default)/*page number*/,
            intval($limit) > 0 ? $limit : static::LIMIT_PER_PAGE/*limit per page*/
        );

    }

    public function getInfo(PaginationInterface $pagination): array
    {
        if ($pagination->count() <= 0) {
            $response['error'] = 'Такой страницы нет';

        } else {
            $response['currentPage'] = $pagination->getCurrentPageNumber();
            $response['totalPages'] = ceil($pagination->getTotalItemCount() / $pagination->getItemNumberPerPage());
            $response['totalItem'] = $pagination->getTotalItemCount();
            $response['totalItemsPerPage'] = $pagination->count();
            $response['limit'] = $pagination->getItemNumberPerPage();

        };


        return $response;

    }
}