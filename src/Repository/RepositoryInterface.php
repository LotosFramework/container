<?php

/*
 * This file is part of the (c)Lotos framework.
 *
 * (c) McLotos <mclotos@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lotos\Container\Repository;

use Lotos\Container\Definition;
use Ds\Collection as CollectionInterface;

interface RepositoryInterface
{
    public function saveClass($class) : void;
    public function saveInterface($interface) : void;
    public function setAlias(string $alias) : void;
    public function getByAlias(string $alias) : ? Definition;
    public function getByInterface(string $interface) : CollectionInterface;
    public function checkExists(string $alias) : bool;
    public function addTypedParam(string $method, string $paramType, string $paramEntity) : void;
    public function addParam(string $method, $paramValue) : void;
    public function addParams(string $method, CollectionInterface $params) : void;
}
