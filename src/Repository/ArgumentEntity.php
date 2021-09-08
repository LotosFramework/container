<?php

declare(strict_types=1);

namespace Lotos\Container\Repository;

/**
 * Класс ArgumentEntity Сущность аргумента сохраняемых методов
 *
 * @author McLotos <mclotos@xakep.ru>
 * @copyright https://github.com/LotosFramework/Container/COPYRIGHT.md
 * @license https://github.com/LotosFramework/Container/LICENSE.md
 * @package Lotos\Container
 * @subpackage Repository
 * @version 1.7
 */
class ArgumentEntity
{

    /**
     * @var mixed $default Значение по умолчанию для аргумента
     */
    private mixed $default = null;

    /**
     * Конструктор принимает список параметров создаваемого аргумента
     *
     * @method __construct
     * @param string $type Тип создаваемого аргумента
     * @param string $name Имя создаваемого аргумента
     * @param mixed $value Значение создаваемого аргумента
     */
    public function __construct(
        private string $type,
        private string $name,
        private mixed $value
    )
    {}

    /**
     * Метод getType возвращает тип созданного аргумента
     *
     * @method getType
     * @return string
     */
    public function getType() : string
    {
        return $this->type;
    }

    /**
     * Метод getName возвращает имя созданного аргумента
     *
     * @method getName
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * Метод getValue возвращает значение созданного аргумента
     *
     * @method getValue
     * @return mixed
     */
    public function getValue() : mixed
    {
        return $this->value;
    }

    /**
     * Метод hasDefault возвращает наличие значения по умолчанию
     *
     * @method hasDefault
     * @return bool true если есть значение по умолчанию или false
     */
    public function hasDefault() : bool
    {
        return $this->default !== null;
    }

    /**
     * Метод getDefault возвращает значение по умолчанию созданного аргумента
     *
     * @method getDefault
     * @return mixed
     */
    public function getDefault() : mixed
    {
        return $this->default;
    }

    /**
     * Метод setDefault устанавливает значение по умолчанию
     *
     * @method setDefault
     * @param mixed $newValue Значение по умолчанию для аргумента
     * @return void
     */
    public function setDefault(mixed $newValue) : void
    {
        $this->default = $newValue;
    }

}
