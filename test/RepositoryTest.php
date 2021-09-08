<?php

declare(strict_types=1);

namespace LotosTest\Container;

use PHPUnit\Framework\TestCase;

use Lotos\Container\RepositoryFactory;
use Lotos\Container\Repository;
use Lotos\Collection\{CollectionFactory, Collection};
use Lotos\Container\Repository\Exception\RepositoryException;
use Lotos\Container\Repository\{
    ArgumentsCollectionFactory,
    ArgumentsCollection,
    ArgumentEntity,
    RepositoryInterface
};

class testRepositoryClass{}
class testRepositoryClass2{}
interface testInterface{}
interface testInterface2{}

class RepositoryTest extends TestCase
{
    /**
     * @test
     * @requires PHP >= 8.0
     * @covers RepositoryFactory::createRepository
     */
    public function createRepository() : RepositoryInterface
    {
        $repository = RepositoryFactory::createRepository(CollectionFactory::createCollection());
        $this->assertInstanceOf(
            Repository::class,
            $repository,
            'Не удалось получить класс Repository из фабрики'
        );
        return $repository;
    }

    /**
     * @test
     * @requires PHP >= 8.0
     * @covers Repository::saveClass
     * @covers Repository::getByClass
     * @depends createRepository
     */
    public function saveClass(RepositoryInterface $repository) : RepositoryInterface
    {
        $repository->saveClass(testRepositoryClass::class);
        $this->assertEquals(
            testRepositoryClass::class,
            $repository->getByClass(testRepositoryClass::class)->getClass(),
            'Не удалось получить правильный класс из репозитория'
        );
        return $repository;
    }

    /**
     * @test
     * @requires PHP >= 8.0
     * @covers Repository::saveInstance
     * @depends saveClass
     */
    public function saveInstance(RepositoryInterface $repository) : RepositoryInterface
    {
        $repository->saveInstance(new testRepositoryClass);
        $this->assertInstanceOf(
            testRepositoryClass::class,
            $repository->getByClass(testRepositoryClass::class)->getInstance(),
            'Не удалось получить правильный объект из репозитория'
        );
        return $repository;
    }

    /**
     * @test
     * @requires PHP >= 8.0
     * @covers Repository::saveInterface
     * @depends saveInstance
     */
    public function saveInterface(RepositoryInterface $repository) : RepositoryInterface
    {
        $repository->saveInterface(testInterface::class);
        $this->assertContains(
            testInterface::class,
            $repository->getByClass(testRepositoryClass::class)->getInterfaces()->toArray(),
            'Не удалось получить интерфейс из репозитория'
        );
        return $repository;
    }

    /**
     * @test
     * @requires PHP >= 8.0
     * @covers Repository::getByInterface
     * @depends saveInterface
     */
    public function saveSeveralInterfacesForSingleClass(RepositoryInterface $repository) : void
    {
        $repository->saveInterface(testInterface2::class);
        $this->assertContains(
            testInterface::class,
            $repository->getByClass(testRepositoryClass::class)->getInterfaces()->toArray(),
            'Не удалось получить первый сохраненный интерфейс из репозитория'
        );
        $this->assertContains(
            testInterface2::class,
            $repository->getByClass(testRepositoryClass::class)->getInterfaces()->toArray(),
            'Не удалось получить второй сохраненный интерфейс из репозитория'
        );
        $this->assertEquals(
            $repository->getByInterface(testInterface::class),
            $repository->getByInterface(testInterface2::class),
            'Объекты, полученные по интерфейсам не совпадают'
        );
        $this->assertEquals(
            $repository->getByInterface(testInterface::class)->last(),
            $repository->getByClass(testRepositoryClass::class),
            'Объект, полученный по интерфейсу не совпадает с объектом, полученным по классу'
        );
    }

    /**
     * @test
     * @requires PHP >= 8.0
     * @covers Repository::setAlias
     * @covers Repository::checkExists
     * @covers Repository::getByAlias
     * @depends saveInterface
     */
    public function setAlias(RepositoryInterface $repository) : RepositoryInterface
    {
        $this->assertFalse(
            $repository->checkExists('test'),
            'Смените алиас. Такой алиас уже занят'
        );
        $repository->setAlias('test');

        $this->assertTrue(
            $repository->checkExists('test'),
            'Проверьте алиас. Нет сущностей с таким алиасом'
        );

        $this->assertEquals(
            'test',
            $repository->getByClass(testRepositoryClass::class)->getAlias(),
            'Не удалось получить сохраненный алиас из репозитория'
        );

        $this->assertEquals(
            testRepositoryClass::class,
            $repository->getByAlias('test')->getClass(),
            'Не удалось получить сохраненный класс по алиасу'
        );
        return $repository;
    }

    /**
     * @test
     * @requires PHP >= 8.0
     * @covers Repository::ensureInstantiable
     * @covers Repository::ensureUniqueClass
     * @depends setAlias
     * @dataProvider ensureInstantiableExceptionsProvider
     */
    public function ensureInstantiableExceptions(
        string $class,
        string $exceptionType,
        string $exceptionMessage,
        RepositoryInterface $repository) : void
    {
        $this->expectException($exceptionType);
        $this->expectExceptionMessage($exceptionMessage);
        $repository->saveClass($class);
    }

    public function ensureInstantiableExceptionsProvider() : array
    {
        return [
            'interface is not instantiable' => [
                testInterface::class,
                RepositoryException::class,
                'LotosTest\Container\testInterface can\'t be instantiable. It\'s not a class.'
            ],
            'class does not exists' => [
                'fail',
                RepositoryException::class,
                'Class "fail" does not exist'
            ],
            'class already registered' => [
                testRepositoryClass::class,
                RepositoryException::class,
                'Class ' . testRepositoryClass::class . ' already registered and can\'t be registered again'
            ],
        ];
    }

    /**
     * @test
     * @requires PHP >= 8.0
     * @covers Repository::ensureValidInterface
     * @covers Repository::ensureUniqueInterface
     * @depends setAlias
     * @dataProvider ensureValidInterfaceExceptionsProvider
     */
    public function ensureValidInterfaceExceptions(
        string $interface,
        string $exceptionType,
        string $exceptionMessage,
        RepositoryInterface $repository) : void
    {
        $this->expectException($exceptionType);
        $this->expectExceptionMessage($exceptionMessage);
        $repository->saveInterface($interface);
    }

    public function ensureValidInterfaceExceptionsProvider() : array
    {
        return [
            'interface does not exists' => [
                'fail',
                RepositoryException::class,
                'Class "fail" does not exist'
            ],
            'interface already registered' => [
                testInterface::class,
                RepositoryException::class,
                'Interface ' . testInterface::class . ' already registered and can\'t be registered again'
            ],
            'invalid interface' => [
                testRepositoryClass::class,
                RepositoryException::class,
                'LotosTest\Container\testRepositoryClass is can\'t be implemented. It\'s not a interface.'
            ],
        ];
    }

    /**
     * @test
     * @requires PHP >= 8.0
     * @covers Repository::setPriority
     * @depends setAlias
     * @depends ensureValidInterfaceExceptions
     */
    public function setPriority(RepositoryInterface $repository) : void
    {
        $repository->setPriority(1);
        $this->assertEquals(
            1,
            $repository->getByAlias('test')->getPriority(),
            'Проритет не установлен или не совпадает.'
        );
    }

    /**
     * @test
     * @requires PHP >= 8.0
     * @covers Repository::addParam
     * @depends setAlias
     * @depends ensureValidInterfaceExceptions
     * @dataProvider paramProvider
     */
    public function addParam(string $methodName, ArgumentEntity $param, int $count, RepositoryInterface $repository) : void
    {
        $repository->addParam($methodName, $param);
        $this->assertEquals(
            $repository
                ->getByAlias('test')
                ->getMethod($methodName)
                ->getArguments()
                ->count(),
            $count,
            'Количество аргументов не совпадает'
        );
        $this->assertEquals(
            $param->getType(),
            $repository
                ->getByAlias('test')
                ->getMethod($methodName)
                ->getArguments()
                ->last()
                ->getType(),
            'Тип аргументов не совпадает'
        );
    }

    public function paramProvider() : array
    {
        return [
            ['foo', new ArgumentEntity(type: 'int', name: 'foo', value: 1), 1],
            ['foo', new ArgumentEntity(type: 'string', name: 'bar', value: 'test'), 2],
            ['foo', new ArgumentEntity(type: 'array', name: 'baz', value: ['foo', 'bar']), 3],
            ['foo', new ArgumentEntity(type: 'bool', name: 'qux', value: true), 4],
        ];
    }

    /**
     * @test
     * @requires PHP >= 8.0
     * @covers Repository::addParams
     * @depends setAlias
     * @dataProvider paramsProvider
     */
    public function addParams(
        string $methodName,
        ArgumentsCollection $params,
        RepositoryInterface $repository
    ) : void
    {
        $repository->addParams($methodName, $params);
        $this->assertEquals(
            $repository
                ->getByAlias('test')
                ->getMethod($methodName)
                ->getArguments(),
            $params
        );

    }

    public function paramsProvider() : array
    {
        return [
            [
                'baz',
                ArgumentsCollectionFactory::createCollection(
                    [
                        new ArgumentEntity(type: 'null', name: 'foo', value: null),
                        new ArgumentEntity(type: 'string', name: 'bar', value: 'test'),
                        new ArgumentEntity(type: 'int', name: 'baz', value: 1),
                    ]
                )
            ],
            [
                'baz2',
                ArgumentsCollectionFactory::createCollection(
                    [
                        new ArgumentEntity(type: 'array', name: 'foo', value: []),
                        new ArgumentEntity(type: 'bool', name: 'bar', value: true),
                    ]
                )
            ],
        ];
    }
}
