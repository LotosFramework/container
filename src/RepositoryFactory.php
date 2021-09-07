<?php

declare(strict_types=1);

namespace Lotos\Container;

use Lotos\Container\Repository\RepositoryInterface;
use Lotos\Collection\Collection;

class RepositoryFactory
{
    public static function createRepository(Collection $collection) : RepositoryInterface
    {
        return new Repository($collection);
    }
}
