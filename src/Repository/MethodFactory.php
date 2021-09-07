<?php

declare(strict_types=1);

namespace Lotos\Container\Repository;

class MethodFactory
{
    public static function createMethod(string $method, ArgumentsCollection $arguments) : MethodInstance
    {
        return new MethodInstance(
            name: $method,
            arguments: $arguments
        );
    }
}
