<?php

namespace Nsm\DoctrinePaginator;

use Hateoas\Configuration\Route;
use Hateoas\Representation\CollectionRepresentation;
use Hateoas\Representation\PaginatedRepresentation;

class HateosPaginatorFactory
{
    /**
     * @var null
     */
    private $pageParameterName;

    /**
     * @var null
     */
    private $limitParameterName;

    /**
     * @param null $pageParameterName
     * @param null $limitParameterName
     */
    public function __construct($pageParameterName = null, $limitParameterName = null)
    {
        $this->pageParameterName = $pageParameterName;
        $this->limitParameterName = $limitParameterName;
    }

    /**
     * @param DoctrinePaginatorDecorator $pager
     * @param Route $route
     * @param null $inline
     *
     * @return PaginatedRepresentation
     */
    public function createRepresentation(DoctrinePaginatorDecorator $pager, Route $route, $inline = null)
    {
        if (null === $inline) {
            $inline = new CollectionRepresentation($pager->getCurrentPageResults());
        }

        return new PaginatedRepresentation(
            $inline,
            $route->getName(),
            // PaginatedRepresentation::__construct exprects an array
            (array) $route->getParameters(),
            $pager->getCurrentPageNumber(),
            $pager->getMaxPerPageNumber(),
            $pager->getTotalPageCount(),
            $this->getPageParameterName(),
            $this->getLimitParameterName(),
            $route->isAbsolute(),
            $pager->getTotalResultCount()
        );
    }

    /**
     * @return string
     */
    public function getPageParameterName()
    {
        return $this->pageParameterName;
    }

    /**
     * @return string
     */
    public function getLimitParameterName()
    {
        return $this->limitParameterName;
    }
}
