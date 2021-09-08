<?php

declare(strict_types=1);

namespace Lotos\Container\Repository;

/**
 * Класс ArgumentsCollectionFactory можно использовать для удобного получения Коллекции аргументов
 *
 * @author McLotos <mclotos@xakep.ru>
 * @copyright https://github.com/LotosFramework/Container/COPYRIGHT.md
 * @license https://github.com/LotosFramework/Container/LICENSE.md
 * @package Lotos\Container
 * @subpackage Repository
 * @version 1.7
 */
class ArgumentsCollectionFactory
{
    /**
     * Метод createCollection создает коллекцию аргументов
     *
     * @method createCollection
     * @param array|null $arguments Список аргументов, которые нужно сохранить
     * @return Lotos\Container\Repository\ArgumentsCollection
     */
    public static function createCollection(?array $arguments = null) : ArgumentsCollection
    {
        return new ArgumentsCollection($arguments);
    }
}
