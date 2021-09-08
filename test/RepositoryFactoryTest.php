<?php

declare(strict_types=1);

namespace LotosTest\Container;

use PHPUnit\Framework\TestCase;

use Lotos\Container\{RepositoryFactory, Repository};
use Lotos\Collection\CollectionFactory;

class RepositoryFactoryTest extends TestCase
{
    /**
     * @test
     * @requires PHP >= 8.0
     * @covers RepositoryFactory::createRepository
     */
    public function createRepository() : void
    {
        $collection = CollectionFactory::createCollection();
        $this->assertInstanceOf(
            Repository::class,
            RepositoryFactory::createRepository($collection),
            'Не удалось получить класс Repository из фабрики'
        );
    }
}
