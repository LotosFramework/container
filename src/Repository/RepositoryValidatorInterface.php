<?php

declare(strict_types=1);

namespace Lotos\Container\Repository;

use Ds\Collection as CollectionInterface;

/**
 * Интерфейс RepositoryValidatorInterface
 *
 * @author McLotos <mclotos@xakep.ru>
 * @copyright https://github.com/LotosFramework/Container/COPYRIGHT.md
 * @license https://github.com/LotosFramework/Container/LICENSE.md
 * @package Lotos\Container
 * @subpackage Repository
 * @version 1.7
 */
interface RepositoryValidatorInterface
{

    /**
     * Метод ensureUniqueClass проверяет что сохраняемый класс уникален
     *
     * @method ensureUniqueClass
     * @param Lotos\Collection\Collection $repository
     * @param string $class
     * @throws Lotos\Container\Repository\Exception\SaveAlreadySavedClass
     * @return void
     */
    public function ensureUniqueClass(CollectionInterface $repository, $class) : void;

    /**
     * Метод ensureUniqueInterface проверяет что сохраняемый интерфейс уникален
     *
     * @method ensureUniqueInterface
     * @param Lotos\Collection\Collection $repository
     * @param string $interface
     * @throws Lotos\Container\Repository\Exception\SaveAlreadySavedInterface
     * @return void
     */
    public function ensureUniqueInterface(CollectionInterface $repository, $interface) : void;

    /**
     * Метод ensureInstantiable проверяет что сохраняется именно класс
     *
     * @method ensureInstantiable
     * @param string $class
     * @throws Lotos\Container\Repository\Exception\WrongArgumentTypeException
     * @return void
     */
    public function ensureInstantiable($class) : void;

    /**
     * Метод ensureValidInterface проверяет что передан действительно интерфейс
     *
     * @method ensureValidInterface
     * @param string $interface
     * @throws Lotos\Container\Repository\Exception\WrongArgumentTypeException
     * @return void
     */
    public function ensureValidInterface($interface) : void;

    /**
     * Метод ensureHasRegisteredDefinition проверяет что у сущности есть сохраненная реализация
     *
     * @method ensureHasRegisteredDefinition
     * @param Lotos\Collection\Collection $repository
     * @param string $class
     * @throws Lotos\Container\Repository\Exception\NotFoundRegisteredRealisationException
     * @return void
     */
    public function ensureHasRegisteredDefinition(Collection $repository, string $class) : void
}
