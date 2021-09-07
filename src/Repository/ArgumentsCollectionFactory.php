<?php

declare(strict_types=1);

namespace Lotos\Container\Repository;

class ArgumentsCollectionFactory
{
    public static function createCollection(?array $arguments = null) : ArgumentsCollection
    {
        return new ArgumentsCollection($arguments);
    }
}
