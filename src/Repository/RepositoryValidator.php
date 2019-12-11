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

use Ds\Collection as CollectionInterface;
use \ReflectionClass;
use Lotos\Container\Exception\WrongArgumentTypeException;
use Lotos\Container\Repository\Exception\{
    SaveAlreadySavedClass,
    SaveAlreadySavedInterface,
    RepositoryException
};

class RepositoryValidator implements RepositoryValidatorInterface
{

    public function ensureUniqueClass(CollectionInterface $repository, $class) : void
    {
        if($repository->where('class', $class)->count() > 0) {
            throw new SaveAlreadySavedClass('Class ' . $class . ' already registered and can\'t be registered again.');
        }
    }

    public function ensureUniqueInterface(CollectionInterface $repository, $interface) : void
    {
        if($repository->contains($interface)) {
            throw new SaveAlreadySavedInterface('Interface ' . $interface . ' already registered and can\'t be registered again.');
        }
    }

    public function ensureInstantiable($class) : void
    {
        if(!(new ReflectionClass($class))->isInstantiable()) {
            throw new WrongArgumentTypeException($class .' can\'t be instantiable. It\'s not a class.');
        }
    }

    public function ensureValidInterface($interface) : void
    {

        if(!(new ReflectionClass($interface))->isInterface()) {
            throw new WrongArgumentTypeException($interface . ' is can\'t be implemented. It\'s not a interface.');
        }
    }


}
