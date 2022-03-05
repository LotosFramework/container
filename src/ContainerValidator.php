<?php

declare(strict_types=1);

namespace Lotos\DI\Container;

use Lotos\DI\Repository\Exception\NotFoundRegisteredRealisationException;
use Lotos\DI\Container\Exception\{GettedIdIsInterface, GettedIdIsAlias, GettedIdIsClass};
use \ReflectionClass;

/**
 * Trait ContainerValidator валидирует параметры, передаваемые в контейнер
 *
 * @author McLotos <mclotos@xakep.ru>
 * @copyright https://github.com/LotosFramework/Container/COPYRIGHT.md
 * @license https://github.com/LotosFramework/Container/LICENSE.md
 * @package Lotos\DI
 * @subpackage Container
 * @version 2.0.0
 */
trait ContainerValidator
{
    /**
     * Метод ensureHasId проверяет что есть
     *
     * @method ensureHasId
     * @param string $id Идентификатор по которому проверяем что есть зарегистрированные реализации
     * @throws Lotos\DI\Repository\Exception\NotFoundRegisteredRealisationException
     * @return void
     */
    private function ensureHasId(string $id) : void
    {
        if (!$this->has($id)) {
            throw new NotFoundRegisteredRealisationException('Element with id ' . $id . ' not found in Repository');
        }
    }

    /**
     * Метод isAlias проверяет что передан алиас, по которому есть зарегистрированный класс
     *
     * @method isAlias
     * @param string $id Алиас,
     * @throws Lotos\DI\Container\Exception\GettedIdIsAlias
     * @return void
     */
    private function isAlias(string $id) : void
    {
        if ($this->repository->getByAlias($id) !== null) {
            throw new GettedIdIsAlias;
        }
    }

    /**
     * Метод isInterface проверяет что передан интерфейс
     *
     * @method isInterface
     * @param string $id Путь к интерфейсу
     * @throws Lotos\DI\Container\Exception\GettedIdIsInterface
     * @return void
     */
    private function isInterface(string $id) : void
    {
        if ((new ReflectionClass($id))->isInterface()) {
            throw new GettedIdIsInterface;
        }
    }

    /**
     * Метод isClass проверяет что передан класс
     *
     * @method isClass
     * @param string $id Путь к rkfccклассу
     * @throws Lotos\DI\Container\Exception\GettedIdIsClass
     * @return void
     */
    private function isClass(string $id) : void
    {
        if ((new ReflectionClass($id))->isInstantiable()) {
            throw new GettedIdIsClass;
        }
    }
}
