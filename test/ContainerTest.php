<?php

declare(strict_types=1);

namespace LotosTest\DI\Container;

use PHPUnit\Framework\TestCase;

use Lotos\DI\Container\{Container, ContainerFactory};
use Lotos\DI\Repository\Factories\RepositoryFactory;
use Lotos\DI\Builder\BuilderFactory;
use Lotos\Collection\CollectionFactory;

interface RequestInterface {}

class TestContainerClass implements RequestInterface {}

class ContainerTest extends TestCase
{

    /**
     * @test
     * @requires PHP >= 8.0
     * @covers Container::get
     * @dataProvider listForCases
     */
    public function get(string $class, string $interface, string $alias)
    {
        $container = $this->initContainer();
        $container->saveClass($class)
            ->forInterface($interface)
            ->withAlias($alias);
        $this->assertInstanceOf(
            $class,
            $container->get($interface),
            'Сущность найденная по интерфейсу не соответствует классу'
        );
        $this->assertInstanceOf(
            $class,
            $container->get($class),
            'Сущность найденная по классу не соответствует классу'
        );
        $this->assertInstanceOf(
            $class,
            $container->get($alias),
            'Сущность найденная по алиасу не соответствует классу'
        );
        $this->assertEquals(
            $container->get($interface),
            $container->get($alias),
            'Сущность полученная по интерфейсу не соответствует сущности полученной по алиасу'
        );
        $this->assertEquals(
            $container->get($class),
            $container->get($alias),
            'Сущность полученная по классу не соответствует сущности полученной по алиасу'
        );
        $this->assertEquals(
            $container->get($class),
            $container->get($interface),
            'Сущность полученная по интерфейсу не соответствует сущности полученной по классу'
        );
    }

    public function listForCases() : array
    {
        return [
            [
                TestContainerClass::class,
                RequestInterface::class,
                'requestTest'
            ],
        ];
    }

    public function initContainer() : Container
    {
        $container = ContainerFactory::createContainer(
            repository: RepositoryFactory::createRepository(
                CollectionFactory::createCollection()
            ),
            builder: BuilderFactory::createBuilder(
                collection: CollectionFactory::createCollection(),
                repository: RepositoryFactory::createRepository(
                    CollectionFactory::createCollection()
                )
            )
        );
        return $container;
    }

    /**
     * @test
     * @requires PHP >= 8.0
     * @covers Container::has
     * @dataProvider listForCases
     */
    public function has(string $class, string $interface, string $alias)
    {
        $container = $this->initContainer();
        $this->assertFalse(
            $container->has($alias),
            'Метод has вернул true по пустой коллекции'
        );
        $container->saveClass($class)
            ->forInterface($interface)
            ->withAlias($alias);
        $this->assertTrue(
            $container->has($alias),
            'Метод has вернул false по существующему алиасу'
        );
        $this->assertTrue(
            $container->has($interface),
            'Метод has вернул false по существующему интерфейсу'
        );
        $this->assertTrue(
            $container->has($class),
            'Метод has вернул false по существующему классу'
        );
    }
}
