<?php

declare(strict_types=1);

namespace Lotos\Container;

use Psr\Container\{
    ContainerInterface,
    ContainerExceptionInterface,
    NotFoundExceptionInterface
};
use Lotos\Container\{
    Repository\RepositoryInterface,
    Builder\BuilderInterface,
};
use Lotos\Container\Container\Exception\{
    GettedIdIsAlias,
    GettedIdIsInterface,
    GettedIdIsClass,
    NotFoundException
};
use Lotos\Container\Container\{ContainerValidator, ContainerExtended};
use \InvalidArgumentException;
use Lotos\Collection\Collection;

/**
 * Класс Container используется для обработки зависимостей создаваемых объектов
 *
 * @author McLotos <mclotos@xakep.ru>
 * @copyright https://github.com/LotosFramework/Container/COPYRIGHT.md
 * @license https://github.com/LotosFramework/Container/LICENSE.md
 * @package Lotos\Container
 * @version 1.7
 */
class Container implements ContainerInterface
{
    /**
     * @see Lotos\Container\Container\ContainerValidator
     */
    use ContainerValidator;

    /**
     * @see Lotos\Container\Container\ContainerExtended
     */
    use ContainerExtended;

    /**
     * Контейнер всегда должен получать Репозиторий и Билдер в качестве аргументов.
     * Репозиторий будет нужен для хранения сущностей, а Билдер для их сборки
     *
     * @method __construct
     * @param Lotos\Container\Repository\RepositoryInterface $repository
     * @param Lotos\Container\Builder\RepositoryInterface $builder
     */
    public function __construct(
        private RepositoryInterface $repository,
        private BuilderInterface $builder
    )
    {
    }

    /**
     * @see PSR-11
     */
    public function get(string $id)
    {
        try {
            $this->isAlias($id);
            $this->isInterface($id);
            $this->isClass($id);
            $this->ensureHasId($id);
        } catch (NotFoundExceptionInterface $e) {
            throw new NotFoundException($e->getMessage());
        } catch (GettedIdIsAlias) {
            return $this->builder->build(
                $this->repository->getByAlias($id)->getClass()
            );
        } catch (GettedIdIsInterface) {
            return $this->builder->build(
                $this->repository->getByInterface($id)->first()->getClass()
            );
        } catch(GettedIdIsClass) {
            return $this->builder->build($id);
        }
    }

    /**
     * @see PSR-11
     */
    public function has(string $id) : bool
    {
        return $this->repository->checkExists($id);
    }
}
