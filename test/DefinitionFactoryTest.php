<?php

declare(strict_types=1);

namespace LotosTest\Container;

use PHPUnit\Framework\TestCase;

use Lotos\Container\Repository\{DefinitionFactory, Definition};
use Lotos\Collection\CollectionFactory;

class DefinitionFactoryTest extends TestCase
{
 /**
     * @test
     * @requires PHP >= 8.0
     * @covers DefinitionFactory::createMethod
     */
    public function createMethod() : void
    {
        $this->assertInstanceOf(
            Definition::class,
            DefinitionFactory::createDefinition(
                CollectionFactory::createCollection(),
                CollectionFactory::createCollection()
            ),
            'Не удалось получить класс Definition из фабрики'
        );
    }
}
