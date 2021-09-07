<?php

declare(strict_types=1);

namespace LotosTest\Container;

use PHPUnit\Framework\TestCase;

use Lotos\Container\Repository\{
    MethodFactory,
    MethodInstance,
    ArgumentsCollection,
    ArgumentsCollectionFactory,
    ArgumentEntity
};

class MethodFactoryTest extends TestCase
{
    /**
     * @test
     * @requires PHP >= 8.0
     * @covers MethodFactory::createMethod
     * @dataProvider methodInstances
     */
    public function createMethod(string $method, ArgumentsCollection $arguments) : void
    {
        $this->assertInstanceOf(
            MethodInstance::class,
            MethodFactory::createMethod($method, $arguments),
            'Не удалось получить класс MethodInstance из фабрики'
        );
    }

    public function methodInstances() : array
    {
        $foo = new ArgumentEntity(name: 'foo', type: 'int', value: 1);
        $bar = new ArgumentEntity(name: 'bar', type: 'int', value: 2);
        $baz = new ArgumentEntity(name: 'baz', type: 'int', value: 12);

        return [
            ['foo', ArgumentsCollectionFactory::createCollection([$foo])],
            ['bar', ArgumentsCollectionFactory::createCollection([$foo, $bar])],
            ['baz', ArgumentsCollectionFactory::createCollection([$foo, $bar, $baz])],
        ];
    }
}
