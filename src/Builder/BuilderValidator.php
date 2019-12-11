<?php

/*
 * This file is part of the (c)Lotos framework.
 *
 * (c) McLotos <mclotos@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lotos\Container\Builder;

use \ReflectionClass;
use \ReflectionMethod;
use \ReflectionNamedType;
use Lotos\Container\Builder\Exception\{
    NotInstantiableException,
    ClassHasNotConstructorException,
    ConstructorHasNotParams,
    IgnoredTypeException,
    NullArgumentTypeException,
    NotInterfaceInstanceException,
    NotFoundRegisteredRealisationException,
    NotOneRealisationRegisteredException,
    InstanceHasNoAlias
};
use Ds\Collecion as CollectionInterface;

class BuilderValidator implements BuilderValidatorInterface
{

    private $ignoreTypes = [
        'string', 'int', 'bool', 'object', 'array'
    ];

    public function ensureInstantiable(ReflectionClass $instance) : void
    {
        if(!$instance->isInstantiable()) {
            throw new NotInstantiableException();
        }
    }

    public function ensureHasConstructor(ReflectionClass $instance) : void
    {
        if(is_null($instance->getConstructor())) {
            throw new ClassHasNotConstructorException();
        }
    }

    public function ensureConstructorHasParams(ReflectionMethod $constructor) : void
    {
        if($constructor->getNumberOfParameters() == 0) {
            throw new ConstructorHasNotParams();
        }
    }

    public function ensureNotIgnoredType(ReflectionNamedType $type) : void
    {
        if(in_array($type->getName(), $this->ignoreTypes)) {
            throw new IgnoredTypeException();
        }
    }

    public function ensureNotNullArgumentType(?ReflectionNamedType $type = null) : void
    {
        if(is_null($type)) {
            throw new NullArgumentTypeException();
        }
    }

    public function ensureInstanseIsInterface(ReflectionClass $instance) : void
    {
        if(!$instance->isInterface()) {
            throw new NotInterfaceInstanceException();
        }
    }

    public function ensureHasRegisteredRealisation(CollectionInterface $collection) : void
    {
        if($collection->count() < 1) {
            throw new NotFoundRegisteredRealisationException();
        }
    }

    public function ensureOnlyOneRegisteredRealisation(CollectionInterface $collection) : void
    {
        if($collection->count() !== 1) {
            throw new NotOneRealisationRegisteredException();
        }
    }

    public function ensureHasAlias($instance) : void
    {
        if(is_null($instance->getAlias()))
        {
            throw new InstanceHasNoAlias();
        }
    }

}
