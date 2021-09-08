<?php

declare(strict_types=1);

namespace Lotos\Container\Repository;

use Lotos\Collection\Collection;

/**
 * Класс ArgumentsCollection Коллекция аргументов сохраняемых методов
 *
 * @author McLotos <mclotos@xakep.ru>
 * @copyright https://github.com/LotosFramework/Container/COPYRIGHT.md
 * @license https://github.com/LotosFramework/Container/LICENSE.md
 * @package Lotos\Container
 * @subpackage Repository
 * @version 1.7
 */
class ArgumentsCollection extends Collection implements \Traversable
{
    /**
     * Конструктор принимает список сущностей, которые нужно сохранить в коллекцию
     *
     * @method __construct
     * @param array|null $entities Набор сущностей
     */
    public function __construct(?array $entities = null)
    {
        parent::__construct();
        if (!is_null($entities)) {
            $this->addValidElements($entities);
        }
    }

    /**
     * Метод push принимает аргументы, которые нужно добавить в коллекцию аргументов
     *
     * @method push
     * @param mixed ...$values
     * @return void
     *
     * @example push(['foo', 'bar', 'baz'])
     * @example push('foo', 'bar', 'baz')
     */
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

    /**
     * Метод addValidElement принимает аргумент, который нужно добавить в коллекцию аргументов
     *
     * @method addValidElement
     * @param Lotos\Container\Repository\ArgumentEntity Аргумент, который нужно записать
     * @return void
     */
    private function addValidElement(ArgumentEntity $element) : void
    {
        parent::push($element);
    }

    /**
     * Метод addValidElements массив аргументов, которые нужно добавить в коллекцию аргументов
     *
     * @method addValidElements
     * @param Lotos\Container\Repository\ArgumentEntity Аргумент, который нужно записать
     * @return void
     */
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
