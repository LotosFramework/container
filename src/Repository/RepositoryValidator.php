<?php

declare(strict_types=1);

namespace Lotos\Container\Repository;

use \ReflectionClass;
use \ReflectionException;
use Lotos\Collection\Collection;
use Lotos\Container\Repository\Exception\{
    SaveAlreadySavedClass,
    SaveAlreadySavedInterface,
    WrongArgumentTypeException,
    NotFoundRegisteredRealisationException
};

trait RepositoryValidator
{
    public function ensureUniqueClass(Collection $repository, string $class) : void
    {
        if($repository->where('class', $class)->count() > 0) {
            throw new SaveAlreadySavedClass('Class ' . $class . ' already registered and can\'t be registered again.');
        }
    }

    public function ensureUniqueInterface(Collection $repository, string $interface) : void
    {
        $repository->map(function($entity) use ($interface) {
            if ($entity->getInterfaces()->count() > 0) {
                $entity->getInterfaces()->map(function($item) use ($interface) {
                    if ($item === $interface) {
                        throw new SaveAlreadySavedInterface('Interface ' . $interface . ' already registered and can\'t be registered again.');
                    }
                });
            }
        });
    }

    public function ensureInstantiable(string $class) : void
    {
        try {
            $ref = new ReflectionClass($class);
            if (!$ref->isInstantiable()) {
                throw new WrongArgumentTypeException($class .' can\'t be instantiable. It\'s not a class.');
            }
        } catch(ReflectionException $e) {
            throw new WrongArgumentTypeException($e->getMessage());
        }
    }

    public function ensureValidInterface(string $interface) : void
    {
        try {
            $ref = new ReflectionClass($interface);
            if (!$ref->isInterface()) {
                throw new WrongArgumentTypeException($interface . ' is can\'t be implemented. It\'s not a interface.');
            }
        } catch(ReflectionException $e) {
            throw new WrongArgumentTypeException($e->getMessage());
        }
    }

    public function ensureHasRegisteredDefinition(Collection $repository, string $class) : void
    {
        if ($this->storage->where('class', $class)->count() === 0) {
            throw new NotFoundRegisteredRealisationException('Not found registered realisation for ' . $class);
        }
    }
}
