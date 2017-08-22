NSM Doctrine Paginator 
======================

Simple decorator for `\Doctrine\ORM\Tools\Pagination\Paginator`.

Usage
-----

```php
// $qb is a Doctrine QueryBuilder object
$p = new \Nsm\DoctrinePaginator\DoctrinePaginator($qb);

var_dump(
    array(
        'currentPageNumber' => $p->getCurrentPageNumber(),
        'currentPageResults' => $p->getCurrentPageResults(),
        'currentPageResultCount' => $p->getCurrentPageResultCount(),
        'currentPageFirstResultPositionInTotalResults' => $p->getCurrentPageFirstResultPositionInTotalResults(),
        'currentPageLastResultPositionInTotalResults' => $p->getCurrentPageLastResultPositionInTotalResults(),
        'maxPageNumber' => $p->getMaxPerPageNumber(),
        'hasPreviousPage' => $p->hasPreviousPage($pNum),
        'previousPageNumber' => $p->hasPreviousPage($pNum) ? $p->getPreviousPageNumber($pNum) : false,
        'hasNextPage' => $p->hasNextPage($pNum),
        'nextPageNumber' => $p->hasNextPage($pNum) ? $p->getNextPageNumber($pNum) : false,
        'totalPageCount' => $p->getTotalPageCount(),
        'totalResultCount' => $p->getTotalResultCount(),
        'canPaginate' => $p->canPaginate(),
        'pageRangeForPage' => $p->getPageRangeForPage(3)
     )
);
```

Running Tests
-------------

```bash
composer install
bin/phpspec run
```
