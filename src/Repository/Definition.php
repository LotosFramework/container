<?php

declare(strict_types=1);

namespace Lotos\Container\Repository;

use Lotos\Collection\Collection;

class Definition {

    private ?string $class;
    private ?string $alias = null;
    private ?int $priority = null;
    private $instance;

    public function __construct(
        private Collection $interfaces,
        private Collection $methods
    )
    {
    }

    public function clearPriority() : void
    {
        $this->priority = null;
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

    public function removeInterface(string $interface) : void
    {
        $this->interfaces->remove($this->interfaces->find($interface));
    }

    public function getInterfaces() : Collection
    {
        return $this->interfaces;
    }

    public function addMethod(MethodInstance $method) : void
    {
        $this->methods->push($method);
    }

    public function getMethod(string $name) : ?MethodInstance
    {
        try {
            return $this->methods->where('name', $name)->first();
        } catch(\UnderflowException $e) {
            return MethodFactory::createMethod(
                $name,
                ArgumentsCollectionFactory::createCollection()
            );
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

    public function setInstance(object $instance) : void
    {
        $this->instance = $instance;
    }

    public function getInstance() : ?object
    {
        return $this->instance;
    }
}
