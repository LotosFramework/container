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
    GettedIdIsClass
};
use Lotos\Container\Container\{ContainerValidator, ContainerExtended};
use \InvalidArgumentException;
use Lotos\Collection\Collection;

class Container implements ContainerInterface
{
    use ContainerValidator;
    use ContainerExtended;

    public function __construct(
        private RepositoryInterface $repository,
        private BuilderInterface $builder
    )
    {
    }

    public function get(string $id)
    {
        try {
            $this->ensureHasId($id);
            $this->isAlias($id);
            $this->isInterface($id);
            $this->isClass($id);
        } catch (NotFoundExceptionInterface $e) {
            throw new NotFoundRegisteredRealisationException($e->getMessage());
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

    public function has(string $id) : bool
    {
        return $this->repository->checkExists($id);
    }
}
