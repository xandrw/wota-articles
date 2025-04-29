<?php

declare(strict_types=1);

namespace App\Application\Results;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class QueryPaginator extends Paginator
{
    public function __construct(
        Query|QueryBuilder $query,
        int $pageNumber,
        int $pageSize,
        bool $fetchJoinCollection = true,
    )
    {
        $query->setFirstResult(($pageNumber - 1) * $pageSize)->setMaxResults($pageSize);
        parent::__construct($query, $fetchJoinCollection);
    }
}
