<?php

declare(strict_types=1);

namespace Lotos\Container\Repository;

use Lotos\Collection\Collection;

class DefinitionFactory
{
    public static function createDefinition(Collection $collection) : Definition
    {
        return new Definition($collection->newInstance(), $collection->newInstance());
    }
}
