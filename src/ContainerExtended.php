<?php

declare(strict_types=1);

namespace Lotos\DI\Container;

use Lotos\DI\Container\Exception\GettedIdIsAlias;
use Lotos\DI\Repository\RepositoryInterface;
use Lotos\DI\Repository\Exception\{
    SaveAlreadySavedInterface,
    RepositoryException
};
use Lotos\Collection\Collection;
use \InvalidArgumentException;

/**
 * Trait ContainerExtended раширяет функционал контейнера
 *
 * @author McLotos <mclotos@xakep.ru>
 * @copyright https://github.com/LotosFramework/Container/COPYRIGHT.md
 * @license https://github.com/LotosFramework/Container/LICENSE.md
 * @package Lotos\DI
 * @subpackage Container
 * @version 2.0.0
 */
trait ContainerExtended
{

    /**
     * Метод используется для реализации Текучего Интерфейса,
     * чтобы упростить взаимодействие с DI
     *
     * @method saveClass сохраняет переданный класс в Репозиторий
     * @param string $class Полный путь к сохраняемому классу
     * @throws InvalidArgumentException
     * @return self
     */
    public function saveClass(string $class) : self
    {
        try {
            $this->repository->saveClass($class);
            return $this;
        } catch(RepositoryException $e) {
            throw new InvalidArgumentException($e->getMessage() . PHP_EOL . $e->getTraceAsText());
        }
    }

    /**
     * Метод используется для реализации Текучего Интерфейса,
     * чтобы упростить взаимодействие с DI
     *
     * @method forInterface привязывает интерфейс к только что сохраненному классу
     * @param string $interface Полный путь к сохраняемому интерфейсу
     * @throws RepositoryException Если интерфейс уже привязан к какому-то другому классу
     * @return self
     */
    public function forInterface(string $interface) : self
    {
        try {
            $this->repository->saveInterface($interface);
            return $this;
        } catch(SaveAlreadySavedInterface $e) {
            throw new RepositoryException($e->getMessage() . PHP_EOL . $e->getTraceAsText());
        }
    }

    /**
     * Метод используется для реализации Текучего Интерфейса,
     * чтобы упростить взаимодействие с DI
     *
     * @method setConstructParam Устанавливает параметры конструктора
     * @param string $interface Полный путь к интерфейсу
     * @param object | string $entity Путь к классу или алиас или готовый объект
     * @return self
     */
    public function setConstructParam(string $interface, object | string $entity) : self
    {
        $this->repository->addTypedParam(
            '__construct',
            $interface,
            match(true) {
                is_object($entity) => $entity,
                $this->isRegisteredAlias($entity) => $this->repository->getByAlias($entity)->getClass()
            }
        );
        return $this;
    }

    /**
     * Метод используется для реализации Текучего Интерфейса,
     * чтобы упростить взаимодействие с DI
     *
     * @method setMethodParams Устанавливает параметры метода
     * @param string $method Название метода, для которого устанавливается параметр
     * @param array $aruments Массив готовых аргументов
     * @return self
     */
    public function setMethodParams(string $method, array $arguments) : self
    {
        $this->repository->addParams($method, $arguments);
        return $this;
    }

    /**
     * Метод используется для реализации Текучего Интерфейса,
     * чтобы упростить взаимодействие с DI
     *
     * @method withAlias Устанавливает алиас для объекта
     * @param string $alias Название объекта, по которому его можно будет получить из репозитория
     * @return self
     */
    public function withAlias(string $alias) : self
    {
        $this->repository->setAlias($alias);
        return $this;
    }

    /**
     * Метод используется для реализации Текучего Интерфейса,
     * чтобы упростить взаимодействие с DI
     *
     * @method withPriority Устанавливает приоритет для объекта
     * @param int $priority Приоритет объекта в хранилище,
     *  нужен если к одному интерфейсу привязано несколько объектов
     * @return self
     */
    public function withPriority(int $priority) : self
    {
        $this->repository->setPriority($priority);
        return $this;
    }

    /**
     * Метод используется для реализации Текучего Интерфейса,
     * чтобы упростить взаимодействие с DI
     *
     * @method findByInterface Поиск объекта в репозитории по интерфейсу
     * @param string $interface Интерфейс, по которому производится поиск
     * @return Collection Коллекция сущностей, подходящих под интерфейс
     */
    public function findByInterface(string $interface) : Collection
    {
       return $this->repository->getByInterface($interface);
    }

    /**
     * Метод используется для реализации Текучего Интерфейса,
     * чтобы упростить взаимодействие с DI
     *
     * @method initCollection Обновляет все элементы коллекции, новыми данными
     * @param array $data Массив, который нужно сохранить в качестве новой коллекции
     * @return Collection Коллекция сущностей
     */
    public function initCollection(array $data) : Collection
    {
        return $this->repository->refreshFill($data);
    }

    /**
     * Метод используется для реализации Текучего Интерфейса,
     * чтобы упростить взаимодействие с DI
     *
     * @method call Вызывает метод объекта, сохраненного в коллекции
     * @param string $alias Алиас, по которому нужно найти объект
     * @param string $method Метод, который нужно вызвать у объекта
     * @param array $arguments Массив аргументов, которые нужно передать в вызываемый метод
     * @return Результат работы вызванного метода
     */
    public function call(
        string $alias,
        string $method,
        array $arguments = []
    ) : mixed
    {
        $class = ($this->isRegisteredAlias($alias))
            ? $this->get($alias)
            : $this->builder->build($alias);
        $arguments = (empty($arguments))
            ? ($this->isRegisteredAlias($alias)
                ? $this->repository
                    ->getByAlias($alias)
                    ->getMethod($method)
                    ->getArguments()
                    ->toArray()
                : $arguments)
            : $arguments;

        return call_user_func_array([$class, $method], $arguments);
    }

    /**
     * Метод используется для реализации Текучего Интерфейса,
     * чтобы упростить взаимодействие с DI
     *
     * @method setInstance Сохраняет объект в качестве реализации интерфейса или алиаса
     * @param object $object Объект, который нужно сохранить
     * @return self
     */
    public function setInstance(object $object) : self
    {
        $this->repository->saveInstance($object);
        return $this;
    }

    /**
     * Метод используется для реализации Текучего Интерфейса,
     * чтобы упростить взаимодействие с DI
     *
     * @method isRegisteredAlias Проверяет существование алиаса
     * @param string $alias Алиас, по которому нужно провести проверку
     * @return bool Вернет true, если есть объекты, привязанные к этому алиасу
     */
    public function isRegisteredAlias(string $alias) : bool
    {
        try {
            $this->isAlias($alias);
            return false;
        } catch(GettedIdIsAlias $e) {
            return true;
        }
    }

    /**
     * Метод используется для реализации Текучего Интерфейса,
     * чтобы упростить взаимодействие с DI
     *
     * @method getRepository Вернет весь репозиторий в его текущем состоянии
     * @return RepositoryInterface Заполненный репозиторий
     */
    public function getRepository() : RepositoryInterface
    {
        return $this->repository;
    }
}
