<?php

declare(strict_types=1);

namespace Lotos\Container;

use Lotos\Container\Repository\RepositoryInterface;
use Lotos\Collection\Collection;

/**
 * Класс RepositoryFactory можно использовать для удобного получения репозитория
 *
 * @author McLotos <mclotos@xakep.ru>
 * @copyright https://github.com/LotosFramework/Container/COPYRIGHT.md
 * @license https://github.com/LotosFramework/Container/LICENSE.md
 * @package Lotos\Container
 * @version 1.7
 */
class RepositoryFactory
{
    /**
     * Метод createRepository создает экземпляр объекта репозитория
     *
     * Репозиторий всегда должен получать Коллекцию в качестве аргумента.
     * На базе Коллекции будет создано хранилище сущностей и их состояний
     *
     * @method createRepository
     * @param Lotos\Collection\Collection $collection Коллекция для репозитория
     * @return Lotos\Container\Repository Репозиторий для хранения сущностей
     */
    public static function createRepository(Collection $collection) : RepositoryInterface
    {
        return new Repository($collection);
    }
}



