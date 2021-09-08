<?php

declare(strict_types=1);

namespace Lotos\Container\Repository;

/**
 * Класс MethodFactory можно использовать для удобного получения метода
 *
 * @author McLotos <mclotos@xakep.ru>
 * @copyright https://github.com/LotosFramework/Container/COPYRIGHT.md
 * @license https://github.com/LotosFramework/Container/LICENSE.md
 * @package Lotos\Container
 * @subpackage Repository
 * @version 1.7
 */
class MethodFactory
{
    /**
     * Метод createMethod создает экземпляр объекта метода
     *
     * Метод всегда должен принимать название и список аргументов
     *
     * @method createMethod
     * @param string $method
     * @param Lotos\Container\Repository\ArgumentsCollection $arguments
     * @return Lotos\Container\Repository\MethodInstance
     */
    public static function createMethod(string $method, ArgumentsCollection $arguments) : MethodInstance
    {
        return new MethodInstance(
            name: $method,
            arguments: $arguments
        );
    }
}
