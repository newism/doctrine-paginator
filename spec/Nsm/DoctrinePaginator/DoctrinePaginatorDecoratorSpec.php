<?php

namespace spec\Nsm\DoctrinePaginator;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DoctrinePaginatorDecoratorSpec extends ObjectBehavior
{
    /**
     * @param \Doctrine\ORM\Tools\Pagination\Paginator $paginator
     * @param \Doctrine\ORM\EntityManager $entityManager
     */
    public function let(Paginator $paginator, EntityManager $entityManager)
    {
        // Create a concrete query see: https://github.com/phpspec/prophecy/issues/102
        $query = new Query($entityManager->getWrappedObject());

        $paginator->getQuery()->willReturn($query);
        $paginator->count()->willReturn(95);

        $this->beConstructedWith($paginator);
        $this->setMaxPerPageNumber(10);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Nsm\DoctrinePaginator\DoctrinePaginatorDecorator');
    }

    public function it_should_return_the_expected_get_max_per_page_number()
    {
        $this->getMaxPerPageNumber()->shouldReturn(10);
    }

    public function it_should_return_the_expected_current_page_number()
    {
        $this->setCurrentPageNumber(2);
        $this->getCurrentPageNumber()->shouldReturn(2);
    }

    public function it_should_return_the_expected_total_result_count()
    {
        $this->getTotalResultCount()->shouldReturn(95);
    }

    /**
     * @param \Doctrine\ORM\Tools\Pagination\Paginator $paginator
     */
    public function it_should_return_the_expected_total_page_count(Paginator $paginator)
    {
        $this->getTotalPageCount()->shouldReturn(10);

        $paginator->count()->willReturn(100);
        $this->getTotalPageCount()->shouldReturn(10);
    }

    public function it_should_return_the_expected_query_offset_for_page()
    {
        $this->getPageQueryOffset(1)->shouldReturn(0);
        $this->getPageQueryOffset(2)->shouldReturn(9);
        $this->getPageQueryOffset(3)->shouldReturn(19);
    }

    /**
     * @param \Doctrine\ORM\Tools\Pagination\Paginator $paginator
     */
    public function it_should_return_the_expected_result_count_for_page(Paginator $paginator)
    {
        $this->getPageResultCount(1)->shouldReturn(10);
        $this->getPageResultCount(10)->shouldReturn(5);

        $paginator->count()->willReturn(100);
        $this->getPageResultCount(10)->shouldReturn(10);
    }

    public function it_should_return_the_expected_first_result_position_for_page_in_total_results()
    {
        $this->getPageFirstResultPositionInTotalResults(1)->shouldReturn(1);
        $this->getPageFirstResultPositionInTotalResults(4)->shouldReturn(31);
    }

    public function it_should_return_the_expected_last_result_position_for_page_in_total_results()
    {
        $this->getPageLastResultPositionInTotalResults(1)->shouldReturn(10);
        $this->getPageLastResultPositionInTotalResults(10)->shouldReturn(95);
    }

    public function it_should_return_the_expected_range_of_page_numbers()
    {
        $this->setMaxPerPageNumber(10);
        $this->getPageRangeForPage(1, 3)->shouldReturn([1, 2, 3, 4]);
        $this->getPageRangeForPage(2, 3)->shouldReturn([1, 2, 3, 4, 5]);
        $this->getPageRangeForPage(3, 3)->shouldReturn([1, 2, 3, 4, 5, 6]);
        $this->getPageRangeForPage(4, 3)->shouldReturn([1, 2, 3, 4, 5, 6, 7]);
        $this->getPageRangeForPage(5, 3)->shouldReturn([2, 3, 4, 5, 6, 7, 8]);
        $this->getPageRangeForPage(6, 3)->shouldReturn([3, 4, 5, 6, 7, 8, 9]);
        $this->getPageRangeForPage(7, 3)->shouldReturn([4, 5, 6, 7, 8, 9, 10]);
        $this->getPageRangeForPage(8, 3)->shouldReturn([5, 6, 7, 8, 9, 10]);
        $this->getPageRangeForPage(9, 3)->shouldReturn([6, 7, 8, 9, 10]);
        $this->getPageRangeForPage(10, 3)->shouldReturn([7, 8, 9, 10]);
    }

    public function it_should_return_the_expected_has_previous_page_boolean()
    {
        $this->hasPreviousPage(2)->shouldReturn(true);
        $this->hasPreviousPage(1)->shouldReturn(false);
    }

    public function it_should_return_the_expected_previous_page_number_or_exception()
    {
        $this->getPreviousPageNumber(2)->shouldReturn(1);
        $this->shouldThrow('\Exception')->duringGetPreviousPageNumber(1);
    }

    public function it_should_return_the_expected_has_next_page_boolean()
    {
        $this->hasNextPage(1)->shouldReturn(true);
        $this->hasNextPage(10)->shouldReturn(false);
    }

    public function it_should_return_the_expected_next_page_number_or_exception()
    {
        $this->getNextPageNumber(1)->shouldReturn(2);
        $this->shouldThrow('\Exception')->duringGetNextPageNumber(10);
    }

    /**
     * @param \Doctrine\ORM\Tools\Pagination\Paginator $paginator
     */
    public function it_should_return_the_expected_can_paginate_boolean(Paginator $paginator)
    {
        $this->canPaginate()->shouldReturn(true);

        $paginator->count()->willReturn(10);
        $this->canPaginate()->shouldReturn(false);
    }
}
