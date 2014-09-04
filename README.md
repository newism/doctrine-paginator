NSM Doctrine Paginator 
======================

[![Travis CI](https://travis-ci.org/newism/doctrine-paginator.svg?branch=master)](https://travis-ci.org/newism/doctrine-paginator)

Simple decorator for `\Doctrine\ORM\Tools\Pagination\Paginator`.

Usage
-----

```php
// $qb is a Doctrine QueryBuilder object 
$p = new \Nsm\DoctrinePaginator\DoctrinePaginator($qb);

var_dump(
    array(
        'maxPageNumber' => $p->getMaxPerPageNumber(),
        'currentPageNumer' => $p->getCurrentPageNumber(),
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

Running PhpSpec Tests
---------------------

```bash
composer install
vendor/bin/phpspec run
```
