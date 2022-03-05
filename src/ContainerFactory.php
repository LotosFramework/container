<?php

declare(strict_types=1);

namespace Lotos\DI\Container;

use Psr\Container\ContainerInterface;

use Lotos\DI\{
    Repository\RepositoryInterface,
    Builder\BuilderInterface
};

/**
 * Класс ContainerFactory можно использовать для удобного получения контейнера
 *
 * @author McLotos <mclotos@xakep.ru>
 * @copyright https://github.com/LotosFramework/Container/COPYRIGHT.md
 * @license https://github.com/LotosFramework/Container/LICENSE.md
 * @package Lotos\DI
 * @subpackage Container
 * @version 2.0.0
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
     * @param Lotos\DI\Repository\RepositoryInterface $repository
     * @param Lotos\DI\Builder\RepositoryInterface $builder
     * @return Psr\Container\ContainerInterface
     */
    public static function createContainer(
        RepositoryInterface $repository,
        BuilderInterface $builder
    ) : ContainerInterface
    {
        return new Container(repository: $repository, builder: $builder);
    }
}
