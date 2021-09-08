<?php

declare(strict_types=1);

namespace LotosTest\Container;

use PHPUnit\Framework\TestCase;
use Lotos\Container\Repository\{ArgumentsCollectionFactory, ArgumentsCollection, ArgumentEntity};

class ArgumentsCollectionTest extends TestCase
{
    /**
     * @test
     * @requires PHP >= 8.0
     * @covers ArgumentsCollection::__construct()
     * @dataProvider filler
     */
    public function createCollection($entity) : void
    {
        $collection = new ArgumentsCollection($entity);
        $this->assertInstanceOf(
            ArgumentsCollection::class,
            $collection,
            'Не удалось создать объект коллекции аргументов'
        );
        if ($entity) {
            $this->assertEquals(
                $entity[0],
                $collection->first(),
                'Элемент коллекции не совпадает с переданным аргументом'
            );
        }
    }

    public function filler() : array
    {
        return [
            [null],
            [[new ArgumentEntity(type: 'int', name: 'test', value: 1)]],
        ];
    }

    /**
     * @test
     * @requires PHP >= 8.0
     * @covers ArgumentsCollection::push()
     * @dataProvider pusher
     */
    public function push($entities) : void
    {
        $collection = new ArgumentsCollection();
        foreach($entities as $entity) {
            $collection->push($entity);
        }
        $this->assertEquals(
            $entities,
            $collection->toArray(),
            'Массив элементов коллекции не соответсвует первоначальному массиву'
        );
    }

    public function pusher() : array
    {
        return [
            [
                [new ArgumentEntity(type: 'int', name: 'test', value: 1)],
                [new ArgumentEntity(type: 'int', name: 'test2', value: 2)]
            ]
        ];
    }

    /**
     * @test
     * @requires PHP >= 8.0
     * @covers ArgumentsCollection::__construct()
     * @dataProvider invalidArguments
     */
    public function createInvalid($entities, $exceptionType, $exceptionMessage) : void
    {
        $this->expectException($exceptionType);
        $this->expectExceptionMessage($exceptionMessage);
        $collection = new ArgumentsCollection($entities);
    }

    public function invalidArguments() : array
    {
        return [
            [['test'], \InvalidArgumentException::class, 'Entity must be instance of ArgumentEntity'],
            [[1], \InvalidArgumentException::class, 'Entity must be instance of ArgumentEntity']
        ];
    }

    /**
     * @test
     * @requires PHP >= 8.0
     * @covers ArgumentsCollection::push()
     * @dataProvider invalidArguments
     */
    public function pushInvalid($entities, $exceptionType, $exceptionMessage) : void
    {
        $this->expectException($exceptionType);
        $this->expectExceptionMessage($exceptionMessage);
        $collection = new ArgumentsCollection();
        foreach($entities as $entity) {
            $collection->push($entity);
        }
    }

}
