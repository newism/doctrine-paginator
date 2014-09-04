<?php

namespace Nsm\DoctrinePaginator;

use Doctrine\ORM\Tools\Pagination\Paginator;

class DoctrinePaginatorFactory
{
    /**
     * @param $query
     * @param bool $fetchJoinCollection
     *
     * @return DoctrinePaginatorDecorator
     */
    public function create($query, $fetchJoinCollection = true)
    {
        $paginator = new Paginator($query, $fetchJoinCollection);

        return new DoctrinePaginatorDecorator($paginator);
    }

} 
