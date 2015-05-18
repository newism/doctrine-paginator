<?php

namespace spec\Nsm\DoctrinePaginator;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Setup;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DoctrinePaginatorFactorySpec extends ObjectBehavior
{
    private static $sharedEm;

    public function let()
    {
        if (!isset(self::$sharedEm)) {

            $config = Setup::createAnnotationMetadataConfiguration(array());
            $config->setDefaultQueryHints(array());

            $conn = array(
                'driver' => 'pdo_sqlite',
                'path' => __DIR__.'/db.sqlite',
                'defaultQueryHints' => array(),
            );

            self::$sharedEm = EntityManager::create($conn, $config);
        }
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Nsm\DoctrinePaginator\DoctrinePaginatorFactory');
    }

    public function it_should_return_the_expected_object_when_constructed_with_query_builder()
    {
        $queryBuilder = new QueryBuilder(self::$sharedEm);
        $this->create($queryBuilder)->shouldHaveType('Nsm\DoctrinePaginator\DoctrinePaginatorDecorator');
    }

    public function it_should_return_the_expected_object_when_constructed_with_query(EntityManager $entityManager)
    {
        $query = new Query(self::$sharedEm);
        $this->create($query)->shouldHaveType('Nsm\DoctrinePaginator\DoctrinePaginatorDecorator');
    }

    public function it_should_return_an_invalid_argument_exception_when_constructed_with_an_invalid_argument()
    {
        $this->shouldThrow('\InvalidArgumentException')->during('create', array(false));
    }
}
