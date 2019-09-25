<?php

/*
 * This file is part of the (c)Lotos framework.
 *
 * (c) McLotos <mclotos@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lotos\Container\Repository;

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
