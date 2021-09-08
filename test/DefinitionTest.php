<?php

declare(strict_types=1);

namespace LotosTest\Container;

use PHPUnit\Framework\TestCase;

use Psr\Http\Message\RequestFactoryInterface;
use Lotos\Http\RequestFactory;
use Lotos\Container\Repository\{
    Definition,
    DefinitionFactory,
    MethodFactory,
    MethodInstance,
    ArgumentsCollection,
    ArgumentEntity
};
use Lotos\Collection\{CollectionFactory, Collection};

class testClass {}

class DefinitionTest extends TestCase
{
    /**
     * @test
     * @requires PHP >= 8.0
     * @covers Definition::setClass
     * @covers Definition::getCLass
     */
    public function setClass() : Definition
    {
        $definition = DefinitionFactory::createDefinition(CollectionFactory::createCollection());
        $definition->setClass(testClass::class);

        $this->assertEquals(
            testClass::class,
            $definition->getClass(),
            'Полученный класс не совпадает с первоначальным'
        );

        return $definition;
    }

    /**
     * @test
     * @requires PHP >= 8.0
     * @covers Definition::setPriority
     * @covers Definition::getPriority
     * @covers Definition::clearPriority
     * @depends setClass
     * @dataProvider providePriorities
     */
    public function setPriority(int $priority, Definition $definition) : void
    {
        $this->assertEquals(
            null,
            $definition->getPriority(),
            'Что-то пошло не так'
        );

        $definition->setPriority($priority);
        $this->assertEquals(
            $priority,
            $definition->getPriority(),
            'Вернулся неправильный приоритет'
        );
        $definition->clearPriority();

        $this->assertEquals(
            null,
            $definition->getPriority(),
            'Что-то пошло не так'
        );
    }

    public function providePriorities() : array
    {
        return [
            [1],
            [2],
            [10],
            [20],
            [50],
            [100]
        ];
    }

    /**
     * @test
     * @requires PHP >= 8.0
     * @covers Definition::setAlias
     * @covers Definition::getAlias
     * @depends setClass
     */
    public function setAlias(Definition $definition) : void
    {
        $definition->setAlias('test');
        $this->assertEquals(
            'test',
            $definition->getAlias(),
            'Вернулся неправильный алиас'
        );
    }

    /**
     * @test
     * @requires PHP >= 8.0
     * @covers Definition::addInterface
     * @covers Definition::getInterfaces
     * @covers Definition::removeInterface
     * @depends setClass
     * @dataProvider provideInterfaces
     */
    public function addInterface(string $interface, Collection $interfaces, int $count, Definition $definition)
    {

        $this->assertCount(
            0,
            $definition->getInterfaces()->toArray(),
            'Коллекция интерфейсов не пуста'
        );

        $interfaces->map(function($item) use (&$definition) {
            $definition->addInterface($item);
        });

        $this->assertEquals(
            $interfaces,
            $definition->getInterfaces(),
            'Сохраненные интерфейсы не совпадают с переданными интерфейсами'
        );

        $this->assertContains(
            $interface,
            $definition->getInterfaces()->toArray(),
            'Интерфейс не найден в коллекции интерфейсов'
        );

        $this->assertEquals(
            $count,
            $definition->getInterfaces()->count(),
            'Количество сохраненных интерфейсов не соответствует ожидаемому'
        );

        $interfaces->map(function($item) use (&$definition) {
            $definition->removeInterface($item);
        });

        $this->assertCount(
            0,
            $definition->getInterfaces()->toArray(),
            'Не удалось удалить интерфейсы'
        );

        return $definition;
    }

    public function provideInterfaces() : array
    {
        return [
            ['foo', CollectionFactory::createCollection(['foo']), 1],
            ['bar', CollectionFactory::createCollection(['foo', 'bar']), 2],
            ['baz', CollectionFactory::createCollection(['foo', 'bar', 'baz']), 3],
        ];
    }

    /**
     * @test
     * @requires PHP >= 8.0
     * @covers Definition::addMethod
     * @covers Definition::getMethod
     * @covers Definition::addOrUpdate
     * @depends setClass
     * @dataProvider provideMethods
     */
    public function addMethod(string $methodName, MethodInstance $method, Definition $definition) : void
    {
        $definition->addMethod($method);
        $this->assertEquals(
            $method,
            $definition->getMethod($methodName),
            'Не удалось зарегистрировать метод'
        );
    }

    public function provideMethods() : array
    {
        return [
            [
                'foo',
                MethodFactory::createMethod(
                    'foo',
                    new ArgumentsCollection()
                )
            ],
            [
                'bar',
                MethodFactory::createMethod(
                    'bar',
                    new ArgumentsCollection(
                        [
                            new ArgumentEntity(
                                type: RequestFactoryInterface::class,
                                name: 'requestFactory',
                                value: new RequestFactory
                            )
                        ]
                    )
                )
            ],
            [
                'baz',
                MethodFactory::createMethod(
                    'baz',
                    new ArgumentsCollection(
                        [
                            new ArgumentEntity(
                                type: RequestFactoryInterface::class,
                                name: 'requestFactory',
                                value: new RequestFactory
                            )
                        ]
                    )
                )
            ],
        ];
    }


    /**
     * @test
     * @requires PHP >= 8.0
     * @covers Definition::setInstance
     * @covers Definition::getInstance
     * @depends setClass
     */
     public function setInstance(Definition $definition)
     {
        $obj = new testClass;
        $this->assertEquals(
            null,
            $definition->getInstance(),
            'Объект уже записан'
        );

        $definition->setInstance($obj);

        $this->assertEquals(
            $obj,
            $definition->getInstance(),
            'Не удалось получить правильный объект'
        );
     }
}
