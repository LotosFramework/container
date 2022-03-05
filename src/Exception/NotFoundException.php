<?php

declare(strict_types=1);

namespace Lotos\DI\Container\Exception;

use Psr\Container\NotFoundExceptionInterface;

class NotFoundException extends ContainerException implements NotFoundExceptionInterface
{}
