<?php

declare(strict_types=1);

namespace LotosTest\Container;

use PHPUnit\Framework\TestCase;

use Lotos\Container\Repository\ArgumentEntity;

class ArgumentEntityTest extends TestCase
{

    /**
     * @test
     * @requires PHP >= 8.0
     * @covers ArgumentEntity::getType()
     * @dataProvider provideTestCases
     */
    public function getArgumentType(
        string $type,
        string $name,
        mixed $value,
        mixed $default
    ) : void
    {
        $argument = new ArgumentEntity(name: $name, type: $type, value: $value);
        $this->assertEquals(
            $type,
            $argument->getType(),
            'Тип аргумента неверно сохранился'
        );
    }

    /**
     * @test
     * @requires PHP >= 8.0
     * @covers ArgumentEntity::getName()
     * @dataProvider provideTestCases
     */
    public function getArgumentName(
        string $type,
        string $name,
        mixed $value,
        mixed $default
    ) : void
    {
        $argument = new ArgumentEntity(name: $name, type: $type, value: $value);
        $this->assertEquals(
            $name,
            $argument->getName(),
            'Имя аргумента сохранилось неправильно'
        );
    }

    /**
     * @test
     * @requires PHP >= 8.0
     * @covers ArgumentEntity::getValue()
     * @dataProvider provideTestCases
     */
    public function getArgumentValue(
        string $type,
        string $name,
        mixed $value,
        mixed $default
    ) : void
    {
        $argument = new ArgumentEntity(name: $name, type: $type, value: $value);
        $this->assertEquals(
            $value,
            $argument->getValue(),
            'Значение аргумента сохранилось неправильно'
        );
    }

    /**
     * @test
     * @requires PHP >= 8.0
     * @covers ArgumentEntity::setDefault
     * @covers ArgumentEntity::hasDefault
     * @covers ArgumentEntity::getDefault
     * @dataProvider provideTestCases
     */
    public function setArgumentDefault(
        string $type,
        string $name,
        mixed $value,
        mixed $default
    ) : ArgumentEntity
    {
        $isHasDefault = !is_null($default);
        $argument = new ArgumentEntity(name: $name, type: $type, value: $value);
        $argument->setDefault($default);
        $this->assertEquals(
            $isHasDefault,
            $argument->hasDefault(),
            'Что-то пошло не так.'
        );
        $this->assertEquals(
            $default,
            $argument->getDefault(),
            'Значение по умолчанию не совпадают'
        );
        return $argument;
    }

    public function provideTestCases()
    {
        return [
            ['int', 'foo', 100, 0],
            ['array', 'bar', ['foo','bar', 'baz'], null],
            ['string', 'baz', 'test', null]
        ];
    }
}
