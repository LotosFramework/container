<?php

declare(strict_types=1);

namespace Lotos\Container\Repository;

use Lotos\Collection\Collection;

/**
 * Класс Definition описывает сущность сохраненного объекта репозитория
 *
 * @author McLotos <mclotos@xakep.ru>
 * @copyright https://github.com/LotosFramework/Container/COPYRIGHT.md
 * @license https://github.com/LotosFramework/Container/LICENSE.md
 * @package Lotos\Container
 * @subpackage Repository
 * @version 1.7
 */
class Definition {

    /**
     * @var string $class Класс, на основе которого будет создаваться объект
     */
    private ?string $class;

    /**
     * @var string|nul $alias Алиас для вызова класса
     */
    private ?string $alias = null;

    /**
     * @var string|null $priority Приоритет класса если несколько классов используют одинаковый интерфейс
     */
    private ?int $priority = null;

    /**
     * @var object $instance Готовая сущность объекта, созданная из $class
     */
    private ?object $instance = null;

    /**
     * Конструктор класса принимает два обязательных параметра
     * Коллекцию с интерфейсами и коллекцию с методами будущего объекта
     *
     * @method __construct
     * @param Lotos\Collection\Collection $interfaces Набор интерфейсов, к которым привязан класс
     * @param Lotos\Collection\Collection $methods Набор методов класса
     */
    public function __construct(
        private Collection $interfaces,
        private Collection $methods
    )
    {
    }

    /**
     * Метод clearPriority очищает приоритет сущности
     *
     * @method clearPriority
     * @return void
     */
    public function clearPriority() : void
    {
        $this->priority = null;
    }

    /**
     * Метод setPriority устаналивает приоритет сущности
     *
     * @method setPriority
     * @param int $priority Новый приоритет сущности
     * @return void
     */
    public function setPriority(int $priority) : void
    {
        $this->priority = $priority;
    }

    /**
     * Метод getPriority возвращает текущий приоритет сущности
     *
     * @method getPriority
     * @return int|null
     */
    public function getPriority() : ?int
    {
        return $this->priority;
    }

    /**
     * Метод setClass устанавливает класс, на основе которого создается сущность
     *
     * @method setClass
     * @param string $class Полный путь к классу сущности
     * @return void
     */
    public function setClass(string $class) : void
    {
        $this->class = $class;
    }

    /**
     * Метод getClass возвращает путь к классу сущности
     *
     * @method getClass
     * @return string|null
     */
    public function getClass() : ?string
    {
        return $this->class;
    }

    /**
     * Метод setAlias устаналивает алиас для удобного поиска сущности
     *
     * @method setAlias
     * @param string $alias Новый алиас сущности
     * @return void
     */
    public function setAlias(string $alias) : void
    {
        $this->alias = $alias;
    }

    /**
     * Метод getAlias возвращает текущий алиас сущности
     *
     * @method getAlias
     * @return string|null
     */
    public function getAlias() : ?string
    {
        return $this->alias;
    }

    /**
     * Метод addInterface добавляет интерфейс к сущности
     *
     * @method addInterface
     * @param string $interface Новый интерфейс, к которому будет привязан класс
     * @return void
     */
    public function addInterface(string $interface) : void
    {
        $this->interfaces->push($interface);
    }

    /**
     * Метод removeInterface удаляет интерфейс из зависимостей класса
     *
     * @method removeInterface
     * @param string $interface Путь к удаляемому интерфейсу
     * @return void
     */
    public function removeInterface(string $interface) : void
    {
        $this->interfaces->remove($this->interfaces->find($interface));
    }

    /**
     * Метод getInterfaces возвращает список интерфейсов, привязанных к классу
     *
     * @method getInterfaces
     * @return Lotos\Collection\Collection
     */
    public function getInterfaces() : Collection
    {
        return $this->interfaces;
    }

    /**
     * Метод addMethod добавляет методы класса
     *
     * @method addMethod
     * @param Lotos\Cotainer\Repository\MethodInstance $method Сущность метода
     * @return void
     */
    public function addMethod(MethodInstance $method) : void
    {
        $this->methods->push($method);
    }

    /**
     * Метод getMethod возвращает метод по его имени
     *
     * @method getMethod
     * @param string $name Имя метода, который нужно получить
     * @return Lotos\Container\Repository\MethodInstance
     */
    public function getMethod(string $name) : ?MethodInstance
    {
        try {
            return $this->methods->where('name', $name)->first();
        } catch(\UnderflowException $e) {
            return MethodFactory::createMethod(
                $name,
                ArgumentsCollectionFactory::createCollection()
            );
        }
    }

    /**
     * Метод addOrUpdate сохраняет или обновляет методы привязанные к классу
     *
     * @method addOrUpdate
     * @param Lotos\Container\Repository\MethodInstance $method Создаваемый или изменяемы метод
     * @return void
     */
    public function addOrUpdate(MethodInstance $method) : void
    {
        if($this->methods->where('name', $method->getName())->count() == 0) {
            $this->methods->push($method);
        } else {
            $this->methods->where('name', $method->getName())
                ->first()
                ->getArguments()
                ->push($method->getArguments()->first());
        }
    }

    /**
     * Метод setInstance устанавливает готовый объект на основе класса
     *
     * @method setInstance
     * @param object $instance Полностью готовый объект
     * @return void
     */
    public function setInstance(object $instance) : void
    {
        $this->instance = $instance;
    }

    /**
     * Метод getInstance возвращает объект
     *
     * @method getInstance
     * @return object Возвращается полностью собранный объект
     */
    public function getInstance() : ?object
    {
        return $this->instance;
    }
}
