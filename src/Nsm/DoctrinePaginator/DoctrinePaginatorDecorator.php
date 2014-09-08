<?php

namespace Nsm\DoctrinePaginator;

use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Class DoctrinePaginator
 * @package Nsm\DoctrinePaginator
 *
 * $p = new \Nsm\DoctrinePaginator\DoctrinePaginator($qb);
 * $pNum = $p->getCurrentPageNumber();
 *
 * var_dump(
 *     array(
 *         'maxPageNumber' => $p->getMaxPerPageNumber(),
 *         'currentPageNumber' => $p->getCurrentPageNumber(),
 *         'currentPageResultCount' => $p->getCurrentPageResultCount(),
 *         'currentPageFirstResultOffset' => $p->getCurrentPageFirstResultPositionInTotalResults(),
 *         'currentPageLastResultOffset' => $p->getCurrentPageLastResultPositionInTotalResults(),
 *         'hasPreviousPage' => $p->hasPreviousPage($pNum),
 *         'previousPage' => $p->hasPreviousPage($pNum) ? $p->getPreviousPageNumber($pNum) : false,
 *         'hasNextPage' => $p->hasNextPage($pNum),
 *         'nextPage' => $p->hasNextPage($pNum) ? $p->getNextPageNumber($pNum) : false,
 *         'pageCount' => $p->getTotalPageCount(),
 *         'totalResultCount' => $p->getTotalResultCount(),
 *         'canPaginate' => $p->canPaginate(),
 *         'currentPageOffsetRange' => $p->getPageRangeForPage(3)
 *      )
 * );
 */
class DoctrinePaginatorDecorator
{
    /**
     * @var int
     */
    private $maxPerPageNumber;

    /**
     * @var int
     */
    private $currentPageNumber;

    /**
     * @var \Doctrine\ORM\Tools\Pagination\Paginator
     */
    private $paginator;

    /**
     * @param Paginator $paginator
     * @param int $maxPerPageNumber
     * @param int $currentPageNumber
     */
    public function __construct(Paginator $paginator, $maxPerPageNumber = 50, $currentPageNumber = 1)
    {
        $this->paginator = $paginator;
        $this->setCurrentPageNumber($currentPageNumber);
        $this->setMaxPerPageNumber($maxPerPageNumber);
    }

    /**
     * @return \Doctrine\ORM\Query
     */
    public function getQuery()
    {
        return $this->paginator->getQuery();
    }

    /**
     * @return int
     */
    public function getCurrentPageNumber()
    {
        return $this->currentPageNumber;
    }

    /**
     * @param int $currentPageNumber
     *
     * @return $this
     */
    public function setCurrentPageNumber($currentPageNumber)
    {
        $this->currentPageNumber = $currentPageNumber;

        $this->paginator->getQuery()->setFirstResult($this->getCurrentPageQueryOffset());

        return $this;
    }

    /**
     * @return array
     */
    public function getCurrentPageResults()
    {
        return (array)$this->paginator->getIterator();
    }

    /**
     * Returns the query offset for the page
     *
     * Scenario:
     *  Given there are 95 results
     *  And there are 10 results per page
     *  And the page number is 1
     *  Then there should be 10 pages
     *  And the returned value should be 0
     *
     * Scenario:
     *  Given there are 95 results
     *  And there are 10 results per page
     *  And the page number is 2
     *  Then there should be 10 pages
     *  And the returned value should be 10
     *
     * Scenario:
     *  Given there are 95 results
     *  And there are 10 results per page
     *  And the page number is 3
     *  Then there should be 10 pages
     *  And the returned value should be 20
     *
     * @param int $pageNumber
     *
     * @return int
     */
    public function getPageQueryOffset($pageNumber)
    {
        $pageNumber -= 1;

        return $this->maxPerPageNumber * $pageNumber;
    }

    /**
     * @return int
     */
    public function getCurrentPageQueryOffset()
    {
        return $this->getPageQueryOffset($this->currentPageNumber);
    }

    /**
     * @return int
     */
    public function getCurrentPageResultCount()
    {
        return $this->getPageResultCount($this->currentPageNumber);
    }

    /**
     * Returns the number of results for the page
     *
     * Scenario:
     *  Given there are 95 results
     *  And there are 10 results per page
     *  And the page number is 1
     *  Then there should be 10 pages
     *  And the returned value should be 10
     *
     * Scenario:
     *  Given there are 95 results
     *  And there are 10 results per page
     *  And the page number is 10
     *  Then there should be 10 pages
     *  And the returned value should be 5
     *
     * Scenario:
     *  Given there are 100 results
     *  And there are 10 results per page
     *  And the page number is 10
     *  Then there should be 10 pages
     *  And the returned value should be 10
     *
     * @param int $pageNumber
     *
     * @return int
     */
    public function getPageResultCount($pageNumber)
    {
        return ($pageNumber < $this->getTotalPageCount())
            ? $this->getMaxPerPageNumber()
            : $this->getTotalResultCount() - (($this->getTotalPageCount() - 1) * $this->getMaxPerPageNumber());
    }

    /**
     * Returns the total number of pages
     *
     * Scenario:
     *  Given there are 95 results
     *  And there are 10 results per page
     *  Then there should be 10 pages
     *
     * Scenario:
     *  Given there are 1 results
     *  And there are 10 results per page
     *  Then there should be 1 pages
     *
     * @return int
     */
    public function getTotalPageCount()
    {
        $totalPages = ceil($this->getTotalResultCount() / $this->getMaxPerPageNumber());

        return (int)$totalPages;
    }

    /**
     * @return int
     */
    public function getTotalResultCount()
    {
        return $this->paginator->count();
    }

    /**
     * @return int
     */
    public function getMaxPerPageNumber()
    {
        return $this->maxPerPageNumber;
    }

    /**
     * @param int $maxPerPageNumber
     *
     * @return bool
     */
    public function setMaxPerPageNumber($maxPerPageNumber)
    {
        $this->maxPerPageNumber = (int)$maxPerPageNumber;

        $this->paginator->getQuery()->setMaxResults($this->maxPerPageNumber);

        return false;
    }

    /**
     * @return double
     */
    public function getCurrentPageFirstResultPositionInTotalResults()
    {
        return $this->getPageFirstResultPositionInTotalResults($this->currentPageNumber);
    }

    /**
     * Returns the pages first result position relative to the total results
     *
     * Scenario:
     *  Given there are 95 results
     *  And there are 10 results per page
     *  And the page number is 1
     *  Then there should be 10 pages
     *  And the returned value should be 1
     *
     * Scenario:
     *  Given there are 95 results
     *  And there are 10 results per page
     *  And the page number is 10
     *  Then there should be 10 pages
     *  And the returned value should be 91
     *
     * @param int $pageNumber
     *
     * @return double
     */
    public function getPageFirstResultPositionInTotalResults($pageNumber)
    {
        $pageNumber -= 1;
        $firstPage = ($this->maxPerPageNumber * $pageNumber) + 1;

        return (int)$firstPage;
    }

    /**
     * @return int
     */
    public function getCurrentPageLastResultPositionInTotalResults()
    {
        return $this->getPageLastResultPositionInTotalResults($this->currentPageNumber);
    }

    /**
     * Returns the pages last result position relative to the total results
     *
     * Scenario:
     *  Given there are 95 results
     *  And there are 10 results per page
     *  And the page number is 1
     *  Then there should be 10 pages
     *  And the returned value should be 10
     *
     * Scenario:
     *  Given there are 95 results
     *  And there are 10 results per page
     *  And the page number is 10
     *  Then there should be 10 pages
     *  And the returned value should be 95
     *
     * @param int $pageNumber
     *
     * @return int
     */
    public function getPageLastResultPositionInTotalResults($pageNumber)
    {
        return ($pageNumber < $this->getTotalPageCount())
            ? $pageNumber * $this->getMaxPerPageNumber()
            : $this->getTotalResultCount();
    }

    /**
     * Returns an array of page numbers offset in each direction from the page number
     *
     * Scenario:
     *  Given there are 95 results
     *  And there are 10 results per page
     *  And the page number is 1
     *  And the offset is 3
     *  The expected result should be [1, 2, 3, 4]
     *
     * Scenario:
     *  Given there are 95 results
     *  And there are 10 results per page
     *  And the page number is 5
     *  And the offset is 3
     *  The expected result should be [2, 3, 4, 5, 6, 7, 8]
     *
     * Scenario:
     *  Given there are 95 results
     *  And there are 10 results per page
     *  And the page number is 10
     *  And the offset is 3
     *  The expected result should be [7, 8, 9, 10]
     *
     * @param int $pageNumber
     * @param int $offset
     *
     * @return array
     */
    public function getPageRangeForPage($pageNumber, $offset = 3)
    {
        $firstPage = max($pageNumber - $offset, 1);
        $lastPage = min($pageNumber + $offset, $this->getTotalPageCount());
        $pageRange = range($firstPage, $lastPage);

        return $pageRange;
    }

    /**
     * Returns the previous page number or an exception
     *
     * Scenario:
     *  Given there are 95 results
     *  And there are 10 results per page
     *  And the page number is 2
     *  Then the expected result should be 1
     *
     * Scenario:
     *  Given there are 95 results
     *  And there are 10 results per page
     *  And the page number is 1
     *  Then an exception should be thrown
     *
     * @param int $pageNumber
     *
     * @return mixed
     * @throws \Exception
     */
    public function getPreviousPageNumber($pageNumber)
    {
        if (false === $this->hasPreviousPage($pageNumber)) {
            throw new \Exception('Out of bounds');
        }

        return $pageNumber - 1;
    }

    /**
     * @param int $pageNumber
     *
     * @return bool
     */
    public function hasPreviousPage($pageNumber)
    {
        return $pageNumber > 1;
    }

    /**
     * Returns the next page number or an exception
     *
     * Scenario:
     *  Given there are 95 results
     *  And there are 10 results per page
     *  And the page number is 1
     *  Then the expected result should be 2
     *
     * Scenario:
     *  Given there are 95 results
     *  And there are 10 results per page
     *  And the page number is 10
     *  Then an exception should be thrown
     *
     * @param int $pageNumber
     *
     * @return integer
     * @throws \Exception
     */
    public function getNextPageNumber($pageNumber)
    {
        if (false === $this->hasNextPage($pageNumber)) {
            throw new \Exception('Out of bounds');
        }

        return $pageNumber + 1;
    }

    /**
     * @param int $pageNumber
     *
     * @return bool
     */
    public function hasNextPage($pageNumber)
    {
        return $pageNumber < $this->getTotalPageCount();
    }

    /**
     * Returns if the paginator can paginate
     *
     * Scenario:
     *  Given there are 95 results
     *  And there are 10 results per page
     *  Then the expected result should be true
     *
     * Scenario:
     *  Given there are 10 results
     *  And there are 10 results per page
     *  Then the expected result should be false
     *
     * @return bool
     */
    public function canPaginate()
    {
        return $this->paginator->count() > $this->maxPerPageNumber;
    }
}
