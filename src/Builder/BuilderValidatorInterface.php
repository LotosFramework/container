<?php

declare(strict_types=1);

namespace Lotos\Container\Builder;

use \ReflectionClass;
use \ReflectionMethod;
use \ReflectionNamedType;
use Ds\Sequence as CollectionInterface;

interface BuilderValidatorInterface
{
    /**
     * @throws Lotos\Container\Builder\Exception\NotInstantiableException
     * @return void
     */
    public function ensureInstantiable(ReflectionClass $instance) : void;

    /**
     * @throws Lotos\Container\Builder\Exception\ClassHasNotConstructorException
     * @return void
     */
    public function ensureHasConstructor(ReflectionClass $instance) : void;

    /**
     * @throws Lotos\Container\Builder\Exception\ConstructorHasNotParams
     * @return void
     */
    public function ensureConstructorHasParams(ReflectionMethod $constructor) : void;

    /**
     * @throws Lotos\Container\Builder\Exception\IgnoredTypeException
     * @return void
     */
    public function ensureNotIgnoredType(ReflectionNamedType $type) : void;

    /**
     * @throws Lotos\Container\Builder\Exception\NullArgumentTypeException
     * @return void
     */
    public function ensureNotNullArgumentType(?ReflectionNamedType $type = null) : void;

    /**
     * @throws Lotos\Container\Builder\Exception\NotInterfaceInstanceException
     * @return void
     */
    public function ensureInstanseIsInterface(ReflectionClass $instance) : void;

    /**
     * @throws Lotos\Container\Builder\Exception\NotFoundRegisteredRealisationException
     * @return void
     */
    public function ensureHasRegisteredRealisation(CollectionInterface $collection) : void;

    /**
     * @throws Lotos\Container\Builder\Exception\NotOneRealisationRegisteredException
     * @return void
     */
    public function ensureOnlyOneRegisteredRealisation(CollectionInterface $collection) : void;

    /**
     * @throws Lotos\Container\Builder\Exception\InstanceHasNoAlias
     * @return void
     */
    public function ensureHasAlias($instance) : void;
}
