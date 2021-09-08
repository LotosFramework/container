<?php

declare(strict_types=1);

namespace LotosTest\Container;

use PHPUnit\Framework\TestCase;
use Lotos\Container\Repository\{ArgumentsCollectionFactory, ArgumentsCollection};

class ArgumentsCollectionFactoryTest extends TestCase
{
    /**
     * @test
     * @requires PHP >= 8.0
     * @covers ArgumentsCollectionFactory::createCollection()
     */
    public function createCollection() : void
    {
        $this->assertInstanceOf(
            ArgumentsCollection::class,
            ArgumentsCollectionFactory::createCollection(),
            'Не удалось получить класс ArgumentsCollection из фабрики'
        );
    }

}
