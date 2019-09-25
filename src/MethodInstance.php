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

use Ds\Collection as CollectionInterface;

class MethodInstance {

    private $name;
    private $arguments;

    public function __construct(string $name, CollectionInterface $arguments)
    {
        $this->name = $name;
        $this->arguments = $arguments;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getArguments() : CollectionInterface
    {
        return $this->arguments;
    }
}
