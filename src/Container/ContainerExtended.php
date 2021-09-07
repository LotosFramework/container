<?php

declare(strict_types=1);

namespace Lotos\Container\Container;

use Lotos\Container\Repository\Exception\NotFoundRegisteredRealisationException;
use Lotos\Container\Container\Exception\{GettedIdIsInterface, GettedIdIsAlias, GettedIdIsClass};
use \ReflectionClass;
use Lotos\Container\Repository\RepositoryInterface;

trait ContainerExtended
{
    public function saveClass($class) : self
    {
        try {
            $this->repository->saveClass($class);
            return $this;
        } catch(RepositoryException $e) {
            throw new InvalidArgumentException($e->getMessage() . PHP_EOL . $e->getTraceAsText());
        }
    }

    public function forInterface($interface) : self
    {
        try {
            $this->repository->saveInterface($interface);
            return $this;
        } catch(SaveAlreadySavedInterface $e) {
            throw new RepositoryException($e->getMessage() . PHP_EOL . $e->getTraceAsText());
        }
    }

    public function setConstructParam($interface, $name) : self
    {
        if (is_object($name)) {
            $class = $name;
        } elseif ($this->isAlias($name)) {
            $class = $this->repository->getByAlias($name)->getClass();
        }
        $this->repository->addTypedParam('__construct', $interface, $class);
        return $this;
    }

    public function setMethodParams(string $method, array $arguments) : self
    {
        $this->repository->addParams($method, $arguments);
        return $this;
    }

    public function withAlias(string $alias) : self
    {
        $this->repository->setAlias($alias);
        return $this;
    }

    public function withPriority(int $priority) : self
    {
        $this->repository->setPriority($priority);
        return $this;
    }

    public function setRefreshable() : self
    {
        //@TODO
        return $this;
    }

    public function findByInterface(string $interface) : Collection
    {
       return $this->repository->getByInterface($interface);
    }

    public function initCollection(array $data) : Collection
    {
        return $this->repository->refreshFill($data);
    }

    public function call(string $alias, string $method, array $arguments = [])
    {
        $class = ($this->isAlias($alias))
            ? $this->get($alias)
            : $this->builder->build($alias);
        $arguments = (empty($arguments))
            ? ($this->isAlias($alias)
                ? $this->repository
                    ->getByAlias($alias)
                    ->getMethod($method)
                    ->getArguments()
                    ->toArray()
                : $arguments)
            : $arguments;

        return call_user_func_array([$class, $method], $arguments);
    }

    public function setInstance($object) : self
    {
        $this->repository->saveInstance($object);
        return $this;
    }

    public function getRepository() : RepositoryInterface
    {
        return $this->repository;
    }
}
