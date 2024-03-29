<?php

declare(strict_types=1);

namespace LotosTest\Container;

use PHPUnit\Framework\TestCase;
use Lotos\DI\Container\{
    ContainerFactory,
    Container
};
use Lotos\DI\Repository\Factories\RepositoryFactory;
use Lotos\DI\Builder\BuilderFactory;
use Lotos\Collection\CollectionFactory;

class ContainerFactoryTest extends TestCase
{
    /**
     * @test
     * @requires PHP >= 8.0
     * @covers ContainerFactory::createContainer
     */
    public function createContainer()
    {
        $collection = CollectionFactory::createCollection();
        $repository = RepositoryFactory::createRepository($collection->newInstance());
        $this->assertInstanceOf(
            Container::class,
            ContainerFactory::createContainer(
                $repository,
                BuilderFactory::createBuilder(
                    $repository,
                    $collection->newInstance()
                )
            ),
            'Не удалось получить класс Container из фабрики'
        );
    }
}
