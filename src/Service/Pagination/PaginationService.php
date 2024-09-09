<?php

namespace App\Service\Pagination;

use Doctrine\ORM\Query;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class PaginationService
{
    public function __construct(
        private PaginatorInterface $paginator
    ) {
    }

    public function createPagination(
        Query $query,
        Request $request,
        int $itemsPerPage = 12
    ) {
        return $this->paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $itemsPerPage
        );
    }
}
