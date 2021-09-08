<?php

declare(strict_types=1);

namespace Lotos\Container\Repository;

use Lotos\Collection\Collection;

/**
 * Класс DefinitionFactory можно использовать для удобного получения Описания
 *
 * @author McLotos <mclotos@xakep.ru>
 * @copyright https://github.com/LotosFramework/Container/COPYRIGHT.md
 * @license https://github.com/LotosFramework/Container/LICENSE.md
 * @package Lotos\Container
 * @subpackage Repository
 * @version 1.7
 */
class DefinitionFactory
{
    /**
     * Метод createDefinition создает экземпляр объекта Описания
     *
     * Метод всегда должен принимать Коллекцию
     *
     * @method createDefinition
     * @param Lotos\Collection\Collection $collection
     * @return Lotos\Container\Repository\Definition
     */
    public static function createDefinition(Collection $collection) : Definition
    {
        return new Definition($collection->newInstance(), $collection->newInstance());
    }
}
