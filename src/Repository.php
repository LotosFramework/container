<?php

declare(strict_types=1);

namespace Lotos\Container;

use Lotos\Container\Repository\{
    RepositoryInterface,
    RepositoryValidator,
    DefinitionFactory,
    Definition,
    MethodFactory,
    ArgumentEntity,
    ArgumentsCollectionFactory
};
use \ReflectionClass;
use Lotos\Collection\Collection;
use Lotos\Container\Repository\Exception\{
    WrongArgumentTypeException,
    SaveAlreadySavedClass,
    RepositoryException,
    SaveAlreadySavedInterface,
    NotFoundRegisteredRealisationException
};

class Repository implements RepositoryInterface
{
    use RepositoryValidator;

    public function __construct(
        private Collection $storage
    )
    {
    }

    public function saveClass(string $class) : void
    {
        try {
            $this->ensureInstantiable($class);
            $this->ensureUniqueClass($this->storage, $class);
            $entity = DefinitionFactory::createDefinition(
                (new ReflectionClass($this->storage))->newInstance()
            );
            $entity->setClass($class);
            $this->storage->push($entity);
        } catch(WrongArgumentTypeException | SaveAlreadySavedClass $e) {
            throw new RepositoryException($e->getMessage() . PHP_EOL . $e->getTraceAsString());
        }
    }

    public function getByClass(string $class) : Definition
    {
        try {
            $this->ensureHasRegisteredDefinition($this->storage, $class);
            return $this->storage->where('class', $class)->first();
        } catch(NotFoundRegisteredRealisationException $e) {
            throw new RepositoryException($e->getMessage() . PHP_EOL . $e->getTraceAsString());
        }
    }

    public function saveInterface(string $interface) : void
    {
        try {
            $this->ensureValidInterface($interface);
            $this->ensureUniqueInterface($this->storage, $interface);
            $this->storage->last()->addInterface($interface);
        } catch (SaveAlreadySavedInterface $e) {
            throw new RepositoryException($e->getMessage() . PHP_EOL . $e->getTraceAsString());
        }
    }

    public function addParam(string $method, ArgumentEntity $argument) : void
    {
        $method = MethodFactory::createMethod(
            $method,
            ArgumentsCollectionFactory::createCollection([$argument])
        );
        $this->storage->last()->addOrUpdate($method);
    }

    public function addParams(string $method, Collection $params) : void
    {
        $method = MethodFactory::createMethod($method, $params);
        $this->storage->last()->addOrUpdate($method);
    }

    public function setAlias(string $alias) : void
    {
        $this->storage->last()->setAlias($alias);
    }

    public function getByAlias(string $alias) : ?Definition
    {
        return ($this->storage->where('alias', $alias)->count() > 0)
            ? $this->storage->where('alias', $alias)->first()
            : null;
    }

    public function getByInterface(string $interface) : Collection
    {
       return $this->storage->filter(function($entity) use ($interface) {
            if($entity->getInterfaces()->count() > 0) {
                return (
                    $entity
                        ->getInterfaces()
                        ->filter(
                            function($item) use ($interface) {
                                return ($item == $interface);
                            }
                        )->count() > 0
                );
            }
            return false;
        });
    }

    public function checkExists(string $alias) : bool
    {
        return (
            $this->storage->where('alias', $alias)->count() === 1 ||
            $this->storage->where('class', $alias)->count() === 1 ||
            $this->storage->filter(function($item) use ($alias) {
                return $item->getInterfaces()->contains($alias);
            })->count() > 0
        );
    }

    public function saveInstance(object $object) : void
    {
        $this->storage->last()->setInstance($object);
    }

    public function setPriority(int $priority) : void
    {
        $this->storage->last()->setPriority($priority);
    }
}
