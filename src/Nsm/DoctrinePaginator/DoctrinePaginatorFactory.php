<?php

namespace Nsm\DoctrinePaginator;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class DoctrinePaginatorFactory
{
    /**
     * @param QueryBuilder|Query $query
     * @param bool $fetchJoinCollection
     * @return DoctrinePaginatorDecorator
     */
    public function create($query, $fetchJoinCollection = true)
    {
        if (!$query instanceof Query && !$query instanceof QueryBuilder) {
            throw new \InvalidArgumentException(
                '$query must me either Doctrine\ORM\QueryBuilder or Doctrine\ORM\Query.'
            );
        }

        $paginator = new Paginator($query, $fetchJoinCollection);

        return new DoctrinePaginatorDecorator($paginator);
    }
}
