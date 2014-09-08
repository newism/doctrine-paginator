NSM Doctrine Paginator 
======================

[![Travis CI](https://travis-ci.org/newism/doctrine-paginator.svg?branch=master)](https://travis-ci.org/newism/doctrine-paginator) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/newism/doctrine-paginator/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/newism/doctrine-paginator/?branch=master)

Simple decorator for `\Doctrine\ORM\Tools\Pagination\Paginator`.

Usage
-----

```php
// $qb is a Doctrine QueryBuilder object 
$p = new \Nsm\DoctrinePaginator\DoctrinePaginator($qb);

var_dump(
    array(
        'maxPageNumber' => $p->getMaxPerPageNumber(),
        'currentPageNumber' => $p->getCurrentPageNumber(),
        'currentPageResultCount' => $p->getCurrentPageResultCount(),
        'currentPageFirstResultOffset' => $p->getCurrentPageFirstResultPositionInTotalResults(),
        'currentPageLastResultOffset' => $p->getCurrentPageLastResultPositionInTotalResults(),
        'hasPreviousPage' => $p->hasPreviousPage($pNum),
        'previousPage' => $p->hasPreviousPage($pNum) ? $p->getPreviousPageNumber($pNum) : false,
        'hasNextPage' => $p->hasNextPage($pNum),
        'nextPage' => $p->hasNextPage($pNum) ? $p->getNextPageNumber($pNum) : false,
        'pageCount' => $p->getTotalPageCount(),
        'totalResultCount' => $p->getTotalResultCount(),
        'canPaginate' => $p->canPaginate(),
        'currentPageOffsetRange' => $p->getPageRangeForPage(3)
     )
);
```

Running Tests
-------------

```bash
composer install
vendor/bin/phpspec run
```
