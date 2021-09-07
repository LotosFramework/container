<?php

namespace Lotos\Container\Container;

use Lotos\Container\Repository\Exception\NotFoundRegisteredRealisationException;
use Lotos\Container\Container\Exception\{GettedIdIsInterface, GettedIdIsAlias, GettedIdIsClass};
use \ReflectionClass;

trait ContainerValidator
{
    private function ensureHasId(string $id) : void
    {
        if (!$this->has($id)) {
            throw new NotFoundRegisteredRealisationException('Element with id ' . $id . ' not found in Repository');
        }
    }

    private function isAlias(string $id) : void
    {
        if ($this->repository->getByAlias($id) !== null) {
            throw new GettedIdIsAlias;
        }
    }

    private function isInterface(string $id) : void
    {
        if ((new ReflectionClass($id))->isInterface()) {
            throw new GettedIdIsInterface;
        }
    }

    private function isClass(string $id) : void
    {
        if ((new ReflectionClass($id))->isInstantiable()) {
            throw new GettedIdIsClass;
        }
    }
}
