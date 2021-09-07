<?php

declare(strict_types=1);

namespace Lotos\Container\Builder;

use \ReflectionClass;
use \ReflectionMethod;
use \ReflectionNamedType;
use \ReflectionParameter;
use \ReflectionProperty;
use \ReflectionType;
use \ReflectionUnionType;
use \ReflectionAttribute;

use Lotos\Container\Builder\Exception\{
    ClassHasNotConstructorException,
    ConstructorHasNotParams,
    IgnoredTypeException,
    InstanceHasNoAliasException,
    NotFoundRegisteredRealisationException,
    NotInstantiableException,
    NotInterfaceInstanceException,
    NotOneRealisationRegisteredException,
    NullArgumentTypeException,
    NotFoundRegisteredArgumentsException,
    HasNoTypeException,
    RegisteredArgumentHasInvalidType,
    MethodHasNotParamsException
};
use Lotos\Container\Repository\{Definition, MethodInstance, RepositoryInterface, ArgumentEntity};
use Lotos\Collection\Collection;

trait BuilderValidator
{

    private array $ignoreTypes = [
        'string', 'int', 'bool', 'object', 'array', 'mixed'
    ];

    public function ensureInstantiable(ReflectionClass $instance) : void
    {
        if (!$instance->isInstantiable()) {
            throw new NotInstantiableException($instance->getName() . ' is not instantiable');
        }
    }

    public function ensureHasConstructor(ReflectionClass $instance) : void
    {
        if (is_null($instance->getConstructor())) {
            throw new ClassHasNotConstructorException($instance->getName() . ' has no constructor');
        }
    }

    public function ensureMethodHasParams(ReflectionMethod $method) : void
    {
        if ($method->getNumberOfParameters() == 0) {
            throw new MethodHasNotParamsException(
                $method->getDeclaringClass()->getName() .
                ':' .
                $method->getName() .
                ' has no parameters');
        }
    }

    public function ensureNotIgnoredType(ReflectionNamedType $type) : void
    {
        if (in_array($type->getName(), $this->ignoreTypes)) {
            throw new IgnoredTypeException($type->getName() . ' registered as ignored');
        }
    }

    public function ensureNotNullArgumentType(ReflectionParameter $parameter) : void
    {
        if (is_null($parameter->getType())) {
            throw new NullArgumentTypeException($parameter->getName() . ' has no type');
        }
    }

    public function ensureInstanseIsInterface(ReflectionClass $instance) : void
    {
        if (!$instance->isInterface()) {
            throw new NotInterfaceInstanceException($instance->getName() . ' is not interface');
        }
    }

    public function ensureHasRegisteredRealisation(Collection $collection, string $name) : void
    {
        if ($collection->count() < 1) {
            throw new NotFoundRegisteredRealisationException('Not found registered realisation for ' . $name);
        }
    }

    public function ensureOnlyOneRegisteredRealisation(Collection $collection, string $name) : void
    {
        if ($collection->count() !== 1) {
            throw new NotOneRealisationRegisteredException('Found more than one registered realisation for ' . $name);
        }
    }

    public function ensureHasAlias($instance) : void
    {
        if (is_null($instance->getAlias())) {
            throw new InstanceHasNoAliasException;
        }
    }

    public function ensureMethodHasRegisteredParams(
        RepositoryInterface $repository,
        ReflectionMethod $method
    ) : void
    {
        if (
            $repository->getByClass($method->getDeclaringClass()->getName())
                ->getMethod($method->getDeclaringClass()->getConstructor()->getName())
                ->getArguments()
                ->count() === 0
        ) {
            throw new NotFoundRegisteredArgumentsException;
        }
    }

    public function ensureHasType(ReflectionParameter $parameter) : void
    {
        if ($parameter->hasType() === false) {
            throw new HasNoTypeException;
        }
    }

    public function ensureArgumentHasValidType(
        ArgumentEntity $entity,
        ReflectionParameter $parameter) : void {
        if ($parameter->hasType()) {
            if ($entity->getType() !== $parameter->getType()->getName()) {
                throw new RegisteredArgumentHasInvalidType;
            }
        }
    }
}
