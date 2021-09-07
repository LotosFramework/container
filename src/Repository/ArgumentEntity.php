<?php

declare(strict_types=1);

namespace Lotos\Container\Repository;

class ArgumentEntity
{

    private mixed $default = null;

    public function __construct(
        private string $type,
        private string $name,
        private mixed $value
    )
    {}

    public function getType() : string
    {
        return $this->type;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getValue() : mixed
    {
        return $this->value;
    }

    public function hasDefault() : bool
    {
        return $this->default !== null;
    }

    public function getDefault() : mixed
    {
        return $this->default;
    }

    public function setDefault(mixed $newValue) : void
    {
        $this->default = $newValue;
    }

}
