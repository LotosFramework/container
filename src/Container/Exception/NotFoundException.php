<?php

declare(strict_types=1);

namespace Lotos\Container\Container\Exception;

use Psr\Container\NotFoundExceptionInterface;

class NotFoundException extends \Exception implements NotFoundExceptionInterface
{}
