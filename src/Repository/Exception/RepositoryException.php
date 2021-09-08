<?php

declare(strict_types=1);

namespace Lotos\Container\Repository\Exception;

use Psr\Container\NotFoundExceptionInterface;

class RepositoryException extends \Exception implements NotFoundExceptionInterface
{

}
