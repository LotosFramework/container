<?php

declare(strict_types=1);

namespace LotosTest\Container;

use PHPUnit\Framework\TestCase;

use Lotos\Container\Repository\{MethodFactory, ArgumentsCollectionFactory, ArgumentEntity};

class MethodInstanceTest extends TestCase
{
    /**
     * @test
     * @requires PHP >= 8.0
     * @covers MethodInstance::getName
     * @dataProvider provideMethodNames
     */
    public function getMethodName(string $methodName) : void
    {
        $this->assertEquals(
            MethodFactory::createMethod($methodName, ArgumentsCollectionFactory::createCollection())->getName(),
            $methodName,
            'Вернулось неправильное имя метода'
        );
    }

    public function provideMethodNames() : array
    {
        return [
            ['foo'],
            ['bar'],
            ['baz']
        ];
    }

    /**
     * @test
     * @requires PHP >= 8.0
     * @covers MethodInstance::getArguments
     * @dataProvider provideMethodArguments
     */
    public function getMethodArguments(string $methodName, array $arguments) : void
    {
        $this->assertEquals(
            $arguments,
            MethodFactory::createMethod(
                $methodName,
                ArgumentsCollectionFactory::createCollection($arguments))
                    ->getArguments()
                    ->toArray(),
            'Возвращаемые аргументы не совпадают с переданными'
        );
    }

    public function provideMethodArguments() : array
    {
        return [
            [
                'foo',
                [new ArgumentEntity(name: 'foo', value: 'default', type: 'string')]
            ],
            [
                'bar',
                [
                    new ArgumentEntity(name: 'foo', value: 'default', type: 'string'),
                    new ArgumentEntity(name: 'bar', value: 'default2', type: 'string')
                ]
            ],
            [
                'baz',
                [
                    new ArgumentEntity(name: 'foo', value: 'default', type: 'string'),
                    new ArgumentEntity(name: 'bar', value: 2, type: 'int'),
                    new ArgumentEntity(name: 'baz', value: [1,2], type: 'array'),
                    new ArgumentEntity(name: 'qux', value: null, type: 'mixed')
                ]
            ],
        ];
    }
}
