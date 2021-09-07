<?php

declare(strict_types=1);

namespace Lotos\Container;

use Psr\Container\ContainerInterface;

use Lotos\Container\{
    Repository\RepositoryInterface,
    Builder\BuilderInterface
};

class ContainerFactory
{
    public static function createContainer(RepositoryInterface $repository, BuilderInterface $builder) : ContainerInterface
    {
        return new Container(repository: $repository, builder: $builder);
    }
}
