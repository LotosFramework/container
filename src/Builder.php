<?php

declare(strict_types=1);

namespace Lotos\Container;

use \ReflectionClass;
use \ReflectionParameter;
use Lotos\Container\Repository\RepositoryInterface;
use Lotos\Container\Builder\{
    BuilderInterface,
    BuilderValidator
};
use Lotos\Container\Builder\Exception\{
    BuilderException,
    ClassHasNotConstructorException,
    ConstructorHasNotParams,
    IgnoredTypeException,
    InstanceHasNoAliasException,
    NotFoundRegisteredRealisationException,
    NotInstantiableException,
    NotInterfaceInstanceException,
    NotOneRealisationRegisteredException,
    NullArgumentTypeException,
    NotFoundRegisteredArgumentsException,
    HasNoTypeException,
    RegisteredArgumentHasInvalidType,
    MethodHasNotParamsException
};

use Lotos\Collection\Collection;

/**
 * Класс Builder создает объекты, с использованием всех их зависимостей
 *
 * @author McLotos <mclotos@xakep.ru>
 * @copyright https://github.com/LotosFramework/Container/COPYRIGHT.md
 * @license https://github.com/LotosFramework/Container/LICENSE.md
 * @package Lotos\Container
 * @version 1.7
 */
class Builder implements BuilderInterface
{

    /**
     * @see  Lotos\Container\Builder\BuilderValidator
     */
    use BuilderValidator;

    /**
     *
     * Билдер всегда должен получать Репозиторий и Коллекцию в качестве аргументов.
     * Репозиторий будет нужен для получения из них сущностей,
     *  а Коллекция для временного хранения служебных данных
     *
     * @method __construct
     * @param Lotos\Collection\Collection $collection
     * @param Lotos\Container\Repository\RepositoryInterface $repository
     */
    public function __construct(
        private Collection $collection,
        private RepositoryInterface $repository
    )
    {
    }

    /**
     * @see Lotos\Container\Builder\BuilderInterface::build
     */
    public function build(string $class) : object
    {
        try {
            $reflection = new ReflectionClass($class);
            $this->ensureInstantiable($reflection);
            $this->ensureHasConstructor($reflection);
            $this->ensureMethodHasParams($reflection->getConstructor());
            return $this->getBuilded($reflection);
        } catch(NotInstantiableException $e) {
            throw new BuilderException($e->getMessage() . PHP_EOL . $e->getTraceAsString());
        } catch(ClassHasNotConstructorException) {
            return $reflection->newInstanceWithoutConstructor();
        } catch(MethodHasNotParamsException) {
            return $reflection->newInstance();
        }
    }

    /**
     * Метод getBuilded возвращает созданный объект
     *
     * Вспомогательный метод, нужен чтобы разгрузить метод build
     * и не нарушать SRP
     *
     * @method getBuilded
     * @param \ReflectionClass $reflection
     * @return object Готовый объект, созданный из данных репозитория
     */
    private function getBuilded(ReflectionClass $reflection) : object
    {
        $args = $this->collection->newInstance();
        $this->collection
            ->newInstance($reflection->getConstructor()->getParameters())
            ->map(function($refArg) use (&$args) {
                $this->getConstructorParameters($refArg, $args);
            });
        return $reflection->newInstanceArgs($args->toArray());
    }

    /**
     * Метод getConstructorParameters получает аргументы для конструктора
     *
     * Метод подбирет с репозитория подходящие аргументы для конструктора
     *
     * @method getConstructorParameters
     * @param \ReflectionParameter $parameter
     * @param Lotos\Collection\Collection $args
     * @return void
     */
    private function getConstructorParameters(ReflectionParameter $parameter, Collection &$args) : void
    {
        try {
            $this->ensureHasType($parameter);
            $this->ensureNotIgnoredType($parameter->getType());
            $this->saveTypedArg($parameter, $args);
        } catch(HasNoTypeException | IgnoredTypeException) {
            try {
                $this->saveNotTypedArg($parameter, $args);
            } catch(BuilderException) {
                $this->getNotTypedArgument($parameter, $args);
            }
        } catch(BuilderException) {
            $this->getInterfaceTypedArgument($parameter, $args);
        }
    }

    /**
     * Метод getNotTypedArgument получает значение по умолчанию
     *   для нетипизированныъ аргументов конструкторааргументы для конструктора
     *
     * @method getNotTypedArgument
     * @param \ReflectionParameter $parameter
     * @param Lotos\Collection\Collection $args
     * @return void
     */
    private function getNotTypedArgument(ReflectionParameter $parameter, Collection &$args) : void
    {
        if ($parameter->isDefaultValueAvailable()) {
            $args->push($parameter->getDefaultValue());
        }
    }

    /**
     * Метод getNotTypedArgument получает значение по умолчанию
     *   для нетипизированныъ аргументов конструкторааргументы для конструктора
     *
     * @method getNotTypedArgument
     * @param \ReflectionParameter $parameter
     * @param Lotos\Collection\Collection $args
     * @return void
     */
    private function getInterfaceTypedArgument(ReflectionParameter $parameter, Collection &$args) : void
    {
        $ref = new ReflectionClass($parameter->getType()->getName());
        match(true) {
            $ref->isInterface() => $args->push($this->buildByInterface($ref)),
            $parameter->isDefaultValueAvailable() => $args->push($parameter->getDefaultValue()),
            $parameter->isOptional() => $args->push(null),
            default => $args->push($this->build($ref->getName()))
        };
    }

    /**
     * Метод saveNotTypedArg сохраняет нетипизированные аргументы во временную коллекцию
     *
     * @method saveNotTypedArg
     * @param \ReflectionParameter $parameter
     * @param Lotos\Collection\Collection $args
     * @return void
     */
    private function saveNotTypedArg(ReflectionParameter $parameter, Collection &$args) : void
    {
        try {
            $methodArg = $this->repository
                ->getByClass($parameter->getDeclaringClass()->getName())
                ->getMethod($parameter->getDeclaringClass()->getConstructor()->getName())
                ->getArguments()
                ->where('name', $parameter->getName())
                ->first();
            $this->ensureArgumentHasValidType($methodArg, $parameter);
            $args->push($methodArg->getValue());
        } catch(\UnderflowException $e) {
            throw new BuilderException($e->getMessage() . PHP_EOL . $e->getTraceAsString());
        }
    }

    /**
     * Метод saveTypedArg сохраняет типизированные аргументы во временную коллекцию
     *
     * @method saveNotTypedArg
     * @param \ReflectionParameter $parameter
     * @param Lotos\Collection\Collection $args
     * @return void
     */
    private function saveTypedArg(ReflectionParameter $parameter, Collection &$args) : void
    {
        try {
            $methodArg = $this->repository
                ->getByClass($parameter->getDeclaringClass()->getName())
                ->getMethod($parameter->getDeclaringClass()->getConstructor()->getName())
                ->getArguments()
                ->where('name', $parameter->getName())
                ->where('type', $parameter->getType())->first();
            $this->ensureArgumentHasValidType($methodArg, $parameter);
            $args->push($methodArg->getValue());
        } catch(\UnderflowException $e) {
            throw new BuilderException($e->getMessage() . PHP_EOL . $e->getTraceAsString());
        }
    }

    /**
     * Метод buildByInterface создает объекты для класса по интерфейсам
     *
     * @method buildByInterface
     * @param \ReflectionClass $ref
     * @throws BuilderException Исключение срабатывает если
     *   нет зарегистрированных реализаций
     *   или если больше одной реализации
     * @return object
     */
    private function buildByInterface(ReflectionClass $ref) : object
    {
        try {
            $collection = $this->repository->getByInterface($ref->getName());
            $this->ensureHasRegisteredRealisation($collection, $ref->getName());
            $this->ensureOnlyOneRegisteredRealisation($collection, $ref->getName());
            $registeredRealisation = $collection->first();
            $instance = $registeredRealisation->getInstance();
            if (!empty($instance)) {
                return $instance;
            }
            $this->ensureHasAlias($registeredRealisation);
            $alias = $collection->first()->getAlias();
            $class = $this->repository->getByAlias($alias)->getClass();
            $builded = $this->build($class);
            $registeredRealisation->setInstance($builded);
            return $builded;
        } catch(NotFoundRegisteredRealisationException | NotOneRealisationRegisteredException $e) {
            throw new BuilderException($e->getMessage() . PHP_EOL . $e->getTraceAsString());
        } catch(InstanceHasNoAliasException) {
            $builded = $this->build($registeredRealisation->getClass());
            $registeredRealisation->setInstance($builded);
            return $builded;
        }
    }
}
