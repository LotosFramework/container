<?php

declare(strict_types=1);

namespace Lotos\Container\Builder;

/**
 * Интерфейс BuilderInterface
 *
 * @author McLotos <mclotos@xakep.ru>
 * @copyright https://github.com/LotosFramework/Container/COPYRIGHT.md
 * @license https://github.com/LotosFramework/Container/LICENSE.md
 * @package Lotos\Container
 * @subpackage Repository
 * @version 1.7
 */
interface BuilderInterface
{
    /**
     * Метод build создает экземпляр вызываемого объекта
     *
     * @method build
     * @param string $class Имя создаваемого класса
     * @throws Lotos\Container\Builder\Exception\BuilderException Выдает исключение, если не удалось создать объект
     * @return object Объект созданный на основе класса
     */
    public function build(string $class) : mixed;
}
