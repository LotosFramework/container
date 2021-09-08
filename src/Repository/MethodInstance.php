<?php

declare(strict_types=1);

namespace Lotos\Container\Repository;

/**
 * Класс MethodInstance сущность метода
 *
 * @author McLotos <mclotos@xakep.ru>
 * @copyright https://github.com/LotosFramework/Container/COPYRIGHT.md
 * @license https://github.com/LotosFramework/Container/LICENSE.md
 * @package Lotos\Container
 * @subpackage Repository
 * @version 1.7
 */
class MethodInstance
{
    /**
     * Метод всегда должен получать название и список аргументов
     *
     * @method __construct
     * @param string $name Название описываемого метода
     * @param Lotos\Container\Repository\ArgumentsCollection $arguments Коллекция аргументов
     */
    public function __construct(
        private string $name,
        private ArgumentsCollection $arguments)
    {
    }

    /**
     * Метод getName возвращает название описываемого метода
     *
     * @method getName
     * @return string Название создавемого метода
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * Метод getArguments возвращает коллекцию аргументов метода
     *
     * @method getArguments
     * @return Lotos\Container\Repository\ArgumentsCollection Коллекция аргументов
     */
    public function getArguments() : ArgumentsCollection
    {
        return $this->arguments;
    }
}
