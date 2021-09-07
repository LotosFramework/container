<?php

declare(strict_types=1);

namespace LotosTest\Container;

use PHPUnit\Framework\TestCase;

use Lotos\Container\{
    BuilderFactory,
    Builder,
    RepositoryFactory,
    Repository\ArgumentsCollectionFactory
};


class BuilderFactoryTest extends TestCase
{
    /**
     * @test
     * @requires PHP >= 8.0
     * @covers BuilderFactory::createBuilder
     */
    public function createBuilder() : void
    {
        $collection = ArgumentsCollectionFactory::createCollection();
        $this->assertInstanceOf(
            Builder::class,
            BuilderFactory::createBuilder(
                collection: $collection,
                repository: RepositoryFactory::createRepository($collection)
            ),
            'Не удалось получить класс Builder из фабрики'
        );
    }
}
