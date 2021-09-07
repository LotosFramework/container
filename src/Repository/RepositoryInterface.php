<?php

declare(strict_types=1);

namespace Lotos\Container\Repository;

use Lotos\Collection\Collection;

interface RepositoryInterface
{
    public function saveClass(string $class) : void;
    public function getByClass(string $class) : ?Definition;
    public function saveInterface(string $interface) : void;
    public function addParam(string $method, ArgumentEntity $paramValue) : void;
    public function addParams(string $method, ArgumentsCollection $params) : void;
    public function setAlias(string $alias) : void;
    public function getByAlias(string $alias) : ?Definition;
    public function getByInterface(string $interface) : Collection;
    public function checkExists(string $alias) : bool;
    public function saveInstance(object $object) : void;
    public function setPriority(int $priority) : void;
}
