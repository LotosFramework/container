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

use \ReflectionClass;
use \ReflectionMethod;
use \ReflectionParameter;
use Ds\Collection as CollectionInterface;
use Lotos\Container\Exception\NotRegisteredNamespaceException;
use Lotos\Container\Builder\Exception\{
    BuilderException,
    NotInstantiableException,
    NullArgumentTypeException,
    ClassHasNotConstructorException,
    ConstructorHasNotParams,
    NotFoundRegisteredRealisationException,
    NotOneRealisationRegisteredException,
    InstanceHasNoAlias,
    IgnoredTypeException,
    NotInterfaceInstanceException
};
use Lotos\Container\Repository\RepositoryInterface;
use Lotos\Container\Builder\{
    BuilderInterface,
    BuilderValidator,
    BuilderValidatorInterface
};

class Builder implements BuilderInterface
{

    public function __construct(
        ?RepositoryInterface &$repository = null,
        ?CollectionInterface $collection = null,
        ?BuilderValidatorInterface $validator = null)
    {
        $this->repository = $repository ?? new Repository;
        $this->collection = $collection ?? new Collection;
        $this->validator = $validator ?? new BuilderValidator;
    }

    public function build(string $class)
    {
        try {
            $reflection = new ReflectionClass($class);
            $this->validator->ensureInstantiable($reflection);
            $this->validator->ensureHasConstructor($reflection);
            $constructor = $reflection->getConstructor();

            $this->validator->ensureConstructorHasParams($reflection->getConstructor());
            $parameters = $this->collection->newInstance($constructor->getParameters());
            $arguments = $this->collection->newInstance();
            $parameters->map(function($item) use (&$arguments) {
                $arguments->push($this->getInstance($item));
            });
            return $reflection->newInstanceArgs($arguments->toArray());
        } catch(NotInstantiableException $e) {
            throw new BuilderException($e->getTraceAsString());
        } catch(ClassHasNotConstructorException $e) {
            return $reflection->newInstanceWithoutConstructor();
        } catch(ConstructorHasNotParams $e) {
            return $reflection->newInstance();
        } catch(NotRegisteredNamespaceException $e) {
            throw new BuilderException($e->getTraceAsString());
        }
    }

    private function getInstance(ReflectionParameter $parameter)
    {
        try {
            $type = $parameter->getType();
            $this->validator->ensureNotNullArgumentType($type);
            $this->validator->ensureNotIgnoredType($type);
            $ref = new ReflectionClass("$type");

            $this->validator->ensureInstanseIsInterface($ref);
            $collection = $this->repository->getByInterface($ref->getName());

            $this->validator->ensureHasRegisteredRealisation($collection);
            $this->validator->ensureOnlyOneRegisteredRealisation($collection);
            $registeredRealisation = $collection->first();
            $instance = $registeredRealisation->getInstance();

            if(!empty($instance)) {
                return $instance;
            }

            $this->validator->ensureHasAlias($registeredRealisation);
            $alias = $collection->first()->getAlias();
            $class = $this->repository->getByAlias($alias)->getClass();

            $builded = $this->build($class);
            $registeredRealisation->setInstance($builded);

            return $builded;
        } catch(NotRegisteredNamespaceException $e) {
            throw new BuilderException('Not found registered
                class for interface ' . $reflection->getName() . $e->getTraceAsString());
        } catch(NotFoundRegisteredRealisationException $e) {
            return $this->getDefaultParameterValue($parameter);
        } catch(NotOneRealisationRegisteredException $e) {
            $items = [];
            foreach($collection as $item) {
                $items[$item->getPriority()] = $item;
            }
            ksort($items);
            $item = array_shift($items);
            return $this->build($item->getClass());
        } catch(InstanceHasNoAlias $e) {
            $builded = $this->build($registeredRealisation->getClass());
            $registeredRealisation->setInstance($builded);
            return $builded;
        } catch(NullArgumentTypeException | IgnoredTypeException $e) {
            return $this->getDefaultParameterValue($parameter);
        } catch(NotInterfaceInstanceException $e) {
            return $this->build($type);
        }
    }

    private function getDefaultParameterValue(ReflectionParameter $parameter)
    {
        return ($parameter->isDefaultValueAvailable())
            ? $parameter->getDefaultValue()
            : null;
    }
}
