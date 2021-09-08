<?php

declare(strict_types=1);

namespace Lotos\Container;

use Lotos\Container\{
    Repository\RepositoryInterface,
    Builder\BuilderInterface
};

use Lotos\Collection\Collection;

/**
 * Класс BuilderFactory можно использовать для удобного получения билдера
 *
 * @author McLotos <mclotos@xakep.ru>
 * @copyright https://github.com/LotosFramework/Container/COPYRIGHT.md
 * @license https://github.com/LotosFramework/Container/LICENSE.md
 * @package Lotos\Container
 * @version 1.7
 */
class BuilderFactory
{
    /**
     * Метод createBuilder создает экземпляр объекта билдера
     *
     * Билдер всегда должен получать Репозиторий и Коллекцию в качестве аргументов.
     * Репозиторий будет нужен для получения из них сущностей,
     *  а Коллекция для временного хранения служебных данных
     *
     * @method createBuilder
     * @param Lotos\Container\Repository\RepositoryInterface $repository
     * @param Lotos\Collection\Collection $collection
     * @return Lotos\Container\Builder\BuilderInterface
     */
    public static function createBuilder(
        RepositoryInterface $repository,
        Collection $collection) : BuilderInterface
    {
        return new Builder(repository: $repository, collection: $collection);
    }
}
