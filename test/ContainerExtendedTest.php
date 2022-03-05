<?php

declare(strict_types=1);

namespace LotosTest\Container;

use PHPUnit\Framework\TestCase;

use Lotos\DI\Container\{Container, ContainerFactory};
use Lotos\DI\Repository\Factories\RepositoryFactory;
use Lotos\DI\Builder\BuilderFactory;
use Lotos\Collection\CollectionFactory;

class ContainerExtendedTest extends TestCase
{

    /**
     * @test
     * @requires PHP >= 8.0
     * @covers ContainerExtended::saveClass
     * @covers ContainerExtended::getRepository
     */
    public function saveClass()
    {
        $container = $this->initContainer();
        $container->saveClass(Container::class);
        $this->assertEquals(
            Container::class,
            $container->getRepository()->getByClass(Container::class)->getClass(),
            'Сохраненный в репозитории класс не совпадает с сохраняемым классом'
        );

    }

    public function initContainer() : Container
    {
        $container = ContainerFactory::createContainer(
            repository: RepositoryFactory::createRepository(
                CollectionFactory::createCollection()
            ),
            builder: BuilderFactory::createBuilder(
                collection: CollectionFactory::createCollection(),
                repository: RepositoryFactory::createRepository(
                    CollectionFactory::createCollection()
                )
            )
        );
        return $container;
    }

}
