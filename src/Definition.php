<?php

/*
 * This file is part of the (c)Lotos framework.
 *
 * (c) McLotos <mclotos@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lotos\Container;

use Ds\Collection as CollectionInterface;

class Definition {

    private $class;
    private $interfaces;
    private $methods;
    private $alias;
    private $instance;
    private $priority;

    public function __construct(CollectionInterface $collection)
    {
        $this->interfaces = new $collection;
        $this->methods = new $collection;
    }

    public function setPriority(int $priority) : void
    {
        $this->priority = $priority;
    }

    public function getPriority() : ?int
    {
        return $this->priority;
    }

    public function setClass(string $class) : void
    {
        $this->class = $class;
    }

    public function getClass() : ?string
    {
        return $this->class;
    }

    public function setAlias(string $alias) : void
    {
        $this->alias = $alias;
    }

    public function getAlias() : ?string
    {
        return $this->alias;
    }

    public function addInterface(string $interface) : void
    {
        $this->interfaces->push($interface);
    }

    public function setMethod(MethodInstance $method) : void
    {
        $this->methods->push($method);
    }

    public function getMethod(string $name) : ?MethodInstance
    {
        try {
            return $this->methods->where('name', $name)->first();
        } catch(\UnderflowException $e) {
            return new MethodInstance($name, $this->interfaces->newInstance());
        }
    }

    public function addOrUpdate(MethodInstance $method) : void
    {
        if($this->methods->where('name', $method->getName())->count() == 0) {
            $this->methods->push($method);
        } else {
            $this->methods->where('name', $method->getName())
                ->first()
                ->getArguments()
                ->push($method->getArguments()->first());
        }
    }

    public function getInterfaces() : CollectionInterface
    {
        return $this->interfaces;
    }

    public function setInstance($instance) : void
    {
        $this->instance = $instance;
    }

    public function getInstance()
    {
        return $this->instance;
    }
}
