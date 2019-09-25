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

use Psr\Container\ContainerInterface;
use Ds\Collection as CollectionInterface;
use Lotos\Collection\Collection;
use Lotos\Container\Exception\WrongArgumentTypeException;

class Container implements ContainerInterface
{

    private $repository;
    private $collection;

    public function __construct(
        ?CollectionInterface $collection = null,
        ?RepositoryInterface $repository = null,
        ?BuilderInterface $builder = null
    )
    {
        $this->collection = $collection ?? new Collection;
        $this->repository = $repository ?? new Repository($this->collection);
        $this->builder = $builder ?? new Builder(
            $this->repository,
            $this->collection
        );
    }

    public function get($id)
    {
        if($this->has($id)) {
            return $this->builder->build(
                $this->repository->getByAlias($id)->getClass()
            );
        } else {
            try {
                $this->repository->saveInterface($id);
                return $this->builder->build($this->repository->getByInterface($id)->first()->getClass());
            } catch(WrongArgumentTypeException $e) {
                return $this->builder->build($id);
            }
        }
    }

    public function has($id)
    {
        return $this->repository->checkExists($id);
    }

    public function saveClass($class) : self
    {
        try {
            $this->repository->saveClass($class);
            return $this;
        } catch(SaveAlreadySavedClass $e) {
            throw new RepositoryException($e->getTraceAsText());
        }
    }

    public function forInterface($interface) : self
    {
        try {
            $this->repository->saveInterface($interface);
            return $this;
        } catch(SaveAlreadySavedInterface $e) {
            throw new RepositoryException($e->getTraceAsText());
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
        return $this;
    }

    public function isAlias(string $alias) : bool
    {
        return $this->has($alias);
    }

    public function findByInterface(string $interface) : CollectionInterface
    {
       return $this->repository->getByInterface($interface);
    }

    public function initCollection(array $data) : CollectionInterface
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
                : $arguments) //тут как-то получить аргументы метода
            : $arguments;

        return call_user_func_array([$class, $method], $arguments);
    }

    public function setInstance($object) : self
    {
        $this->repository->saveInstance($object);
        return $this;
    }

}
