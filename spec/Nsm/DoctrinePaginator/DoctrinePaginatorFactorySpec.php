<?php

namespace spec\Nsm\DoctrinePaginator;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DoctrinePaginatorFactorySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Nsm\DoctrinePaginator\DoctrinePaginatorFactory');
    }

    /**
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder
     * @param \Doctrine\ORM\EntityManager $entityManager
     */
    public function it_should_return_the_expected_object_when_constructed_with_query_builder(
        QueryBuilder $queryBuilder,
        EntityManager $entityManager
    )
    {
        // Create a concrete query see: https://github.com/phpspec/prophecy/issues/102
        $query = new Query($entityManager->getWrappedObject());
        $queryBuilder->getQuery()->willReturn($query);

        $this->create($queryBuilder)->shouldHaveType('Nsm\DoctrinePaginator\DoctrinePaginatorDecorator');
    }

    /**
     * @param \Doctrine\ORM\EntityManager $entityManager
     */
    public function it_should_return_the_expected_object_when_constructed_with_query(EntityManager $entityManager)
    {
        // Create a concrete query see: https://github.com/phpspec/prophecy/issues/102
        $query = new Query($entityManager->getWrappedObject());

        $this->create($query)->shouldHaveType('Nsm\DoctrinePaginator\DoctrinePaginatorDecorator');
    }

    public function it_should_return_an_invalid_argument_exception_when_constructed_with_an_invalid_argument()
    {
        $this->shouldThrow('\InvalidArgumentException')->during('create', array(false));
    }
}
