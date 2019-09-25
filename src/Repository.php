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
use Lotos\Container\Repository\{
    RepositoryInterface,
    RepositoryValidator,
    RepositoryValidatorInterface
};
use Lotos\Container\Exception\WrongArgumentTypeException;
use Lotos\Container\Repository\Exception\{
    RepositoryException,
    SaveAlreadySavedClass,
    SaveAlreadySavedInterface
};

class Repository implements RepositoryInterface
{

    private $storage;
    private $collection;

    public function __construct(
        CollectionInterface $collection,
        ?RepositoryValidatorInterface $validator = null
    )
    {
        $this->collection = $collection;
        $this->storage = new $collection;
        $this->validator = $validator ?? new RepositoryValidator;
    }

    public function saveClass($class) : void
    {
        try {
            $this->validator->ensureInstantiable($class);
            $this->validator->ensureUniqueClass($this->storage, $class);
            $entity = new Definition($this->collection);
            $entity->setClass($class);
            $this->storage->push($entity);
        } catch(WrongArgumentTypeException | SaveAlreadySavedClass $e) {
            throw new RepositoryException($e->getMessage());
        }
    }

    public function getByClass(string $class) : Definition
    {
        return $this->storage->where('class', $class)->first();
    }

    public function saveInterface($interface) : void
    {
        try {
            $this->validator->ensureValidInterface($interface);
            $this->validator->ensureUniqueInterface($this->storage, $interface);
            $this->storage->last()->addInterface($interface);
        } catch(SaveAlreadySavedInterface $e) {
            throw new RepositoryException($e->getMessage());
        }
    }

    public function addTypedParam(string $method, string $paramType, $paramEntity) : void
    {
        $arguments = new $this->collection();
        $arguments->push([
            'type' => $paramType,
            'entity' => $paramEntity
        ]);
        $method = new MethodInstance($method, $arguments);
        $this->storage->last()->addOrUpdate($method);
    }

    public function addParam(string $method, $paramValue) : void
    {
        $arguments = new $this->collection();
        $arguments->push([
            'type' => null,
            'entity' => $paramValue
        ]);
        $method = new MethodInstance($method, $arguments);
        $this->storage->last()->addOrUpdate($method);
    }

    public function addParams(string $method, CollectionInterface $params) : void
    {
        $method = new MethodInstance($method, $params);
        $this->storage->last()->addOrUpdate($method);
    }

    public function setAlias(string $alias) : void
    {
        $this->storage->last()->setAlias($alias);
    }

    public function getByAlias(string $alias) : ?Definition
    {
        return $this->storage->where('alias', $alias)->first();
    }

    public function getByInterface(string $interface) : CollectionInterface
    {
       return $this->storage->filter(function($entity) use ($interface) {
            if($entity->getInterfaces()->count() > 0) {
                return ($entity->getInterfaces()->filter(function($item) use ($interface) {
                    return ($item == $interface);
                })->count() > 0);
            }
            return false;
        });
    }

    public function checkExists(string $alias) : bool
    {
        return ($this->storage->where('alias', $alias)->count() == 1);
    }

    public function saveInstance($object) : void
    {
        $this->storage->last()->setInstance($object);
    }

    public function setPriority(int $priority) : void
    {
        $this->storage->last()->setPriority($priority);
    }

}
