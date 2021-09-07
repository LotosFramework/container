<?php

declare(strict_types=1);

namespace Lotos\Container\Repository;

use Ds\Collection as CollectionInterface;

interface RepositoryValidatorInterface
{
    /**
     * @throws Lotos\Container\Repository\Exception\SaveAlreadySavedClass
     * @return void
     */
    public function ensureUniqueClass(CollectionInterface $repository, $class) : void;

    /**
     * @throws Lotos\Container\Repository\Exception\SaveAlreadySavedInterface
     * @return void
     */
    public function ensureUniqueInterface(CollectionInterface $repository, $interface) : void;

    /**
     * @throws Lotos\Container\Exception\WrongArgumentTypeException
     * @return void
     */
    public function ensureInstantiable($class) : void;

    /**
     * @throws Lotos\Container\Exception\WrongArgumentTypeException
     * @return void
     */
    public function ensureValidInterface($interface) : void;
}
