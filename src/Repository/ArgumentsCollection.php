<?php

declare(strict_types=1);

namespace Lotos\Container\Repository;

use Lotos\Collection\Collection;

class ArgumentsCollection extends Collection implements \Traversable
{
    public function __construct(?array $entities = null)
    {
        parent::__construct();
        if (!is_null($entities)) {
            $this->addValidElements($entities);
        }
    }

    public function push(...$values) : void
    {
        foreach($values as $element) {
            if(is_array($element)) {
                $this->addValidElements($element);
            } else {
                if (!($element instanceof ArgumentEntity)) {
                    throw new \InvalidArgumentException('Entity must be instance of ArgumentEntity');
                }
                $this->addValidElement($element);
            }
        }
    }

    private function addValidElement(ArgumentEntity $element) : void
    {
        parent::push($element);
    }

    private function addValidElements(array $entities) : void
    {
        foreach($entities as $entity) {
            if (!($entity instanceof ArgumentEntity)) {
                throw new \InvalidArgumentException('Entity must be instance of ArgumentEntity');
            } else {
                $this->addValidElement($entity);
            }
        }
    }
}
