<?php

declare(strict_types=1);

namespace Lotos\Container\Builder;

interface BuilderInterface
{
    public function build(string $class) : mixed;
}
