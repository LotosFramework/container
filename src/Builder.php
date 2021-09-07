<?php

declare(strict_types=1);

namespace Lotos\Container;

use \ReflectionClass;
use \ReflectionParameter;
use Lotos\Container\Repository\RepositoryInterface;
use Lotos\Container\Builder\{
    BuilderInterface,
    BuilderValidator
};
use Lotos\Container\Builder\Exception\{
    BuilderException,
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

use Lotos\Collection\Collection;

class Builder implements BuilderInterface
{

    use BuilderValidator;

    public function __construct(
        private Collection $collection,
        private RepositoryInterface $repository
    )
    {
    }

    public function build(string $class) : mixed
    {
        try {
            $reflection = new ReflectionClass($class);
            $this->ensureInstantiable($reflection);
            $this->ensureHasConstructor($reflection);
            $this->ensureMethodHasParams($reflection->getConstructor());
            return $this->getBuilded($reflection);
        } catch(NotInstantiableException $e) {
            throw new BuilderException($e->getMessage() . PHP_EOL . $e->getTraceAsString());
        } catch(ClassHasNotConstructorException) {
            return $reflection->newInstanceWithoutConstructor();
        } catch(MethodHasNotParamsException) {
            return $reflection->newInstance();
        }
    }

    private function getBuilded(ReflectionClass $reflection) : object
    {
        $args = $this->collection->newInstance();
        $this->collection
            ->newInstance($reflection->getConstructor()->getParameters())
            ->map(function($refArg) use (&$args) {
                $this->getConstructorParameters($refArg, $args);
            });
        return $reflection->newInstanceArgs($args->toArray());
    }

    private function getConstructorParameters(ReflectionParameter $parameter, Collection &$args) : void
    {
        try {
            $this->ensureHasType($parameter);
            $this->ensureNotIgnoredType($parameter->getType());
            $this->saveTypedArg($parameter, $args);
        } catch(HasNoTypeException | IgnoredTypeException) {
            try {
                $this->saveNotTypedArg($parameter, $args);
            } catch(BuilderException) {
                $this->getNotTypedArgument($parameter, $args);
            }
        } catch(BuilderException) {
            $this->getInterfaceTypedArgument($parameter, $args);
        }
    }

    private function getNotTypedArgument(ReflectionParameter $parameter, Collection &$args) : void
    {
        if ($parameter->isDefaultValueAvailable()) {
            $args->push($parameter->getDefaultValue());
        }
    }

    private function getInterfaceTypedArgument(ReflectionParameter $parameter, Collection &$args) : void
    {
        $ref = new ReflectionClass($parameter->getType()->getName());
        match(true) {
            $ref->isInterface() => $args->push($this->buildByInterface($ref)),
            $parameter->isDefaultValueAvailable() => $args->push($parameter->getDefaultValue()),
            $parameter->isOptional() => $args->push(null),
            default => $args->push($this->build($ref->getName()))
        };
    }

    private function saveNotTypedArg(ReflectionParameter $parameter, Collection &$args) : void
    {
        try {
            $methodArg = $this->repository
                ->getByClass($parameter->getDeclaringClass()->getName())
                ->getMethod($parameter->getDeclaringClass()->getConstructor()->getName())
                ->getArguments()
                ->where('name', $parameter->getName())
                ->first();
            $this->ensureArgumentHasValidType($methodArg, $parameter);
            $args->push($methodArg->getValue());
        } catch(\UnderflowException $e) {
            throw new BuilderException($e->getMessage() . PHP_EOL . $e->getTraceAsString());
        }
    }

    private function saveTypedArg(ReflectionParameter $parameter, Collection &$args) : void
    {
        try {
            $methodArg = $this->repository
                ->getByClass($parameter->getDeclaringClass()->getName())
                ->getMethod($parameter->getDeclaringClass()->getConstructor()->getName())
                ->getArguments()
                ->where('name', $parameter->getName())
                ->where('type', $parameter->getType())->first();
            $this->ensureArgumentHasValidType($methodArg, $parameter);
            $args->push($methodArg->getValue());
        } catch(\UnderflowException $e) {
            throw new BuilderException($e->getMessage() . PHP_EOL . $e->getTraceAsString());
        }
    }

    private function buildByInterface(ReflectionClass $ref) : object
    {
        try {
            $collection = $this->repository->getByInterface($ref->getName());
            $this->ensureHasRegisteredRealisation($collection, $ref->getName());
            $this->ensureOnlyOneRegisteredRealisation($collection, $ref->getName());
            $registeredRealisation = $collection->first();
            $instance = $registeredRealisation->getInstance();
            if (!empty($instance)) {
                return $instance;
            }
            $this->ensureHasAlias($registeredRealisation);
            $alias = $collection->first()->getAlias();
            $class = $this->repository->getByAlias($alias)->getClass();
            $builded = $this->build($class);
            $registeredRealisation->setInstance($builded);
            return $builded;
        } catch(NotFoundRegisteredRealisationException | NotOneRealisationRegisteredException $e) {
            throw new BuilderException($e->getMessage() . PHP_EOL . $e->getTraceAsString());
        } catch(InstanceHasNoAliasException) {
            $builded = $this->build($registeredRealisation->getClass());
            $registeredRealisation->setInstance($builded);
            return $builded;
        }
    }
}
