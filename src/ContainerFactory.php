<?php

declare(strict_types=1);

namespace Lotos\Container;

use Psr\Container\ContainerInterface;

use Lotos\Container\{
    Repository\RepositoryInterface,
    Builder\BuilderInterface
};

/**
 * Класс ContainerFactory можно использовать для удобного получения контейнера
 *
 * @author McLotos <mclotos@xakep.ru>
 * @copyright https://github.com/LotosFramework/Container/COPYRIGHT.md
 * @license https://github.com/LotosFramework/Container/LICENSE.md
 * @package Lotos\Container
 * @version 1.7
 */
class ContainerFactory
{
    /**
     * Метод createContainer создает экземпляр объекта контейнера
     *
     * Контейнер всегда должен получать Репозиторий и Билдер в качестве аргументов.
     * Репозиторий будет нужен для хранения сущностей, а Билдер для их сборки
     *
     * @method createContainer
     * @param Lotos\Container\Repository\RepositoryInterface $repository
     * @param Lotos\Container\Builder\RepositoryInterface $builder
     * @return Psr\Container\ContainerInterface
     */
    public static function createContainer(RepositoryInterface $repository, BuilderInterface $builder) : ContainerInterface
    {
        return new Container(repository: $repository, builder: $builder);
    }
}
