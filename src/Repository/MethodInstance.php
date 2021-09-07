<?php

declare(strict_types=1);

namespace Lotos\Container\Repository;

class MethodInstance {

    public function __construct(
        private string $name,
        private ArgumentsCollection $arguments)
    {
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getArguments() : ArgumentsCollection
    {
        return $this->arguments;
    }
}
