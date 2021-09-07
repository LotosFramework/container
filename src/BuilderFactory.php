<?php

declare(strict_types=1);

namespace Lotos\Container;

use Lotos\Container\{
    Repository\RepositoryInterface,
    Builder\BuilderInterface
};

use Lotos\Collection\Collection;

class BuilderFactory
{
    public static function createBuilder(
        RepositoryInterface $repository,
        Collection $collection) : BuilderInterface
    {
        return new Builder(repository: $repository, collection: $collection);
    }
}
