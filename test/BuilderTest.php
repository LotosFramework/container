<?php

declare(strict_types=1);

namespace LotosTest\Container;

use PHPUnit\Framework\TestCase;

use Lotos\Container\{
    BuilderFactory,
    Builder,
    Builder\BuilderInterface,
    RepositoryFactory,
    Repository\RepositoryInterface,
    Repository\ArgumentEntity,
    Repository\ArgumentsCollectionFactory
};
use Lotos\Collection\CollectionFactory;

class testBuilderClass {}
class testBuilderClass2 {
    public function __construct() {}
}
class testBuilderClass3 {
    public function __construct(private RequestFactoryInterface $requestFactory) {}
    public function getProperties() : array
    {
        return [
            [
                'type' => 'LotosTest\Container\RequestFactoryInterface',
                'value' => $this->requestFactory
            ]
        ];
    }
}
class testBuilderClass4 {
    public function __construct(private $data) {}
    public function getProperties() : array
    {
        return [
            [
                'type' => 'mixed',
                'value' => $this->data
            ]
        ];
    }
}
class testBuilderClass5 {
    public function __construct(private $data1, private $data2) {}
    public function getProperties() : array
    {
        return [
            [
                'type' => 'mixed',
                'value' => $this->data1
            ],
            [
                'type' => 'mixed',
                'value' => $this->data2
            ]
        ];
    }
}
class testBuilderClass6 {
    public function __construct(private $data1, private $data2 = null) {}
    public function getProperties() : array
    {
        return [
            [
                'type' => 'mixed',
                'value' => $this->data1
            ],
            [
                'type' => 'mixed',
                'value' => $this->data2
            ]
        ];
    }
}
class testBuilderClass7 {
    public function __construct(private $data1 = null, private $data2 = null) {}
    public function getProperties() : array
    {
        return [
            [
                'type' => 'mixed',
                'value' => $this->data1
            ],
            [
                'type' => 'mixed',
                'value' => $this->data2
            ]
        ];
    }
}
class testBuilderClass8 {
    public function __construct(private $data1 = null, private $data2 = null) {}
    public function getProperties() : array
    {
        return [
            [
                'type' => 'mixed',
                'value' => $this->data1
            ],
            [
                'type' => 'mixed',
                'value' => $this->data2
            ]
        ];
    }
}
class testBuilderClass9 {
    public function __construct(private $data1 = 1, private $data2 = null) {}
    public function getProperties() : array
    {
        return [
            [
                'type' => 'mixed',
                'value' => $this->data1
            ],
            [
                'type' => 'mixed',
                'value' => $this->data2
            ]
        ];
    }
}
class testBuilderClass10 {
    public function __construct(private $data1 = 1, private $data2 = null) {}
    public function getProperties() : array
    {
        return [
            [
                'type' => 'mixed',
                'value' => $this->data1
            ],
            [
                'type' => 'mixed',
                'value' => $this->data2
            ]
        ];
    }
}
class testBuilderClass11 {
    public function __construct(private $data1 = 1, private $data2 = 2) {}
    public function getProperties() : array
    {
        return [
            [
                'type' => 'mixed',
                'value' => $this->data1
            ],
            [
                'type' => 'mixed',
                'value' => $this->data2
            ]
        ];
    }
}
class testBuilderClass12 {
    public function __construct(private $data1 = 1, private $data2 = 2) {}
    public function getProperties() : array
    {
        return [
            [
                'type' => 'mixed',
                'value' => $this->data1
            ],
            [
                'type' => 'mixed',
                'value' => $this->data2
            ]
        ];
    }
}
class testBuilderClass13 {
    public function __construct(private int $intTest) {}
    public function getProperties() : array
    {
        return [
            [
                'type' => 'int',
                'value' => $this->intTest
            ]
        ];
    }
}
class testBuilderClass14 {
    public function __construct(private ?int $nullableIntTest = null) {}
    public function getProperties() : array
    {
        return [
            [
                'type' => 'int',
                'value' => $this->nullableIntTest
            ]
        ];
    }
}
class testBuilderClass15 {
    public function __construct(private ?int $nullableIntTest = null) {}
    public function getProperties() : array
    {
        return [
            [
                'type' => 'int',
                'value' => $this->nullableIntTest
            ]
        ];
    }
}
class testBuilderClass16 {
    public function __construct(private ?int $requiredNullableIntTest) {}
    public function getProperties() : array
    {
        return [
            [
                'type' => 'int',
                'value' => $this->requiredNullableIntTest
            ]
        ];
    }
}
class testBuilderClass17 {
    public function __construct(private int $intTestWithDefault = 1) {}
    public function getProperties() : array
    {
        return [
            [
                'type' => 'int',
                'value' => $this->intTestWithDefault
            ]
        ];
    }
}
class testBuilderClass18 {
    public function __construct(private int $intTestWithDefault = 1) {}
    public function getProperties() : array
    {
        return [
            [
                'type' => 'int',
                'value' => $this->intTestWithDefault
            ]
        ];
    }
}
class testBuilderClass19 {
    public function __construct(private Request $request) {}
    public function getProperties() : array
    {
        return [
            [
                'type' => 'LotosTest\Container\Request',
                'value' => $this->request
            ]
        ];
    }
}
class testBuilderClass20 {
    public function __construct(private Request $request, private ResponseFactoryInterface $response) {}
    public function getProperties() : array
    {
        return [
            [
                'type' => 'LotosTest\Container\Request',
                'value' => $this->request
            ],
            [
                'type' => 'LotosTest\Container\ResponseFactoryInterface',
                'value' => $this->response
            ]
        ];
    }
}
class testBuilderClass21 {
    public function __construct(private Request $request, private ?ResponseFactory $response) {}
    public function getProperties() : array
    {
        return [
            [
                'type' => 'LotosTest\Container\Request',
                'value' => $this->request
            ],
            [
                'type' => 'LotosTest\Container\ResponseFactory',
                'value' => $this->response
            ]
        ];
    }
}
class testBuilderClass22{
    public function __construct(private int $foo, private array $bar) {}
    public function getProperties() : array
    {
        return [
            [
                'type' => 'int',
                'value' => $this->foo
            ],
            [
                'type' => 'array',
                'value' => $this->bar
            ]
        ];
    }
}
class testBuilderClass23{
    public function __construct(private array $foo, private string $bar) {}
    public function getProperties() : array
    {
        return [
            [
                'type' => 'array',
                'value' => $this->foo
            ],
            [
                'type' => 'string',
                'value' => $this->bar
            ]
        ];
    }
}
class testBuilderClass24{
    public function __construct(private ?array $foo = null, private ?string $bar = null) {}
    public function getProperties() : array
    {
        return [
            [
                'type' => 'array',
                'value' => $this->foo
            ],
            [
                'type' => 'string',
                'value' => $this->bar
            ]
        ];
    }
}
class testBuilderClass25{
    public function __construct(private ?array $foo, private ?string $bar = null) {}
    public function getProperties() : array
    {
        return [
            [
                'type' => 'array',
                'value' => $this->foo
            ],
            [
                'type' => 'string',
                'value' => $this->bar
            ]
        ];
    }
}
interface RequestInterface {}
interface RequestFactoryInterface {}
interface ResponseFactoryInterface{}
class Request implements RequestInterface {public function __construct(private testClass $test){}}
class RequestFactory implements RequestFactoryInterface {public function __construct(private testBuilderClass12 $test){}}
class ResponseFactory implements ResponseFactoryInterface{}

class BuilderTest extends TestCase
{
    /**
     * @test
     * @requires PHP >= 8.0
     * @covers Builder::build
     * @depends initBuilder
     * @dataProvider providerBuildEntities
     */
    public function build($testClass, bool $hasProperties, ?array $properties, BuilderInterface $builder) : void
    {
        $builded = $builder->build($testClass);
        $this->assertInstanceOf(
            $testClass,
            $builded,
            'Не удалось создать объект из класса'
        );

        if ($hasProperties) {
            $this->assertEquals(
                $properties,
                $builded->getProperties()
            );
        }
    }

    public function providerBuildEntities() : array
    {
        return [
            'без конструктора' => [testBuilderClass::class, false, null],
            'конструктор без аргументов' => [testBuilderClass2::class, false, null],
            'аргумент типизирован интерфейсом' => [
                testBuilderClass3::class,
                true,
                [
                    [
                        'type' => 'LotosTest\Container\RequestFactoryInterface',
                        'value' => new RequestFactory(new testBuilderClass12(1100, 1101))
                    ]
                ]
            ],
            'нетипизированный аргумент' => [
                testBuilderClass4::class,
                true,
                [
                    [
                        'type' => 'mixed',
                        'value' => []
                    ]
                ]
            ],
            'два нетипизированных аргумента' => [
                testBuilderClass5::class,
                true,
                [
                    [
                        'type' => 'mixed',
                        'value' => 'foo',
                    ],
                    [
                        'type' => 'mixed',
                        'value' => 10
                    ]
                ]
            ],
            'два нетипизированных аргумента, второй с дефолтным значением null' => [
                testBuilderClass6::class,
                true,
                [
                    [
                        'type' => 'mixed',
                        'value' => 100
                    ],
                    [
                        'type' => 'mixed',
                        'value' => null
                    ]
                ]
            ],
            'два нетипизированных аргумента, оба с дефолтным значением null без Arguments' => [
                testBuilderClass7::class,
                true,
                [
                    [
                        'type' => 'mixed',
                        'value' => null
                    ],
                    [
                        'type' => 'mixed',
                        'value' => null
                    ]
                ]
            ],
            'два нетипизированных аргумента, оба с дефолтным значением null с Arguments' => [
                testBuilderClass8::class,
                true,
                [
                    [
                        'type' => 'mixed',
                        'value' => 'foo'
                    ],
                    [
                        'type' => 'mixed',
                        'value' => 1000
                    ]
                ]
            ],
            'два нетипизированных аргумента, 1st default int, 2nd default null без Arguments' => [
                testBuilderClass9::class,
                true,
                [
                    [
                        'type' => 'mixed',
                        'value' => 1,
                    ],
                    [
                        'type' => 'mixed',
                        'value' => null
                    ]
                ]
            ],
            'два нетипизированных аргумента, 1st default int, 2nd default null с Arguments' => [
                testBuilderClass10::class,
                true,
                [
                    [
                        'type' => 'mixed',
                        'value' => 1001,
                    ],
                    [
                        'type' => 'mixed',
                        'value' => 1010
                    ]
                ]
            ],
            'два нетипизированных аргумента, с дефолтными числовыми значениями без Arguments' => [
                testBuilderClass11::class,
                true,
                [
                    [
                        'type' => 'mixed',
                        'value' => 1
                    ],
                    [
                        'type' => 'mixed',
                        'value' => 2
                    ]
                ]
            ],
            'два нетипизированных аргумента, с дефолтными числовыми значениями с Arguments' => [
                testBuilderClass12::class,
                true,
                [
                    [
                        'type' => 'mixed',
                        'value' => 1100
                    ],
                    [
                        'type' => 'mixed',
                        'value' => 1101
                    ]
                ]
            ],
            'типизированный скалярный аргумент' => [
                testBuilderClass13::class,
                true,
                [
                    [
                        'type' => 'int',
                        'value' => 1111
                    ]
                ]
            ],
            'типизированный скалярный аргумент с возможностью быть null' => [
                testBuilderClass14::class,
                true,
                [
                    [
                        'type' => 'int',
                        'value' => null
                    ]
                ]
            ],
            'типизированный скалярный аргумент с возможностью быть null с Arguments' => [
                testBuilderClass15::class,
                true,
                [
                    [
                        'type' => 'int',
                        'value' => 1010
                    ]
                ]
            ],
            'типизированный скалярный обязательный аргумент с возможностью быть null с Arguments' => [
                testBuilderClass16::class,
                true,
                [
                    [
                        'type' => 'int',
                        'value' => null
                    ]
                ]
            ],
            'типизированный скалярный аргумент со значением по умолчанию без Arguments' => [
                testBuilderClass17::class,
                true,
                [
                    [
                        'type' => 'int',
                        'value' => 1
                    ]
                ]
            ],
            'типизированный скалярный аргумент со значением по умолчанию с Arguments' => [
                testBuilderClass18::class,
                true,
                [
                    [
                        'type' => 'int',
                        'value' => 1010
                    ]
                ]
            ],
            'аргумент типизирован классом' => [
                testBuilderClass19::class,
                true,
                [
                    [
                        'type' => 'LotosTest\Container\Request',
                        'value' => new Request(new testClass)
                    ]
                ]],
            'два аргумента типизированных классами' => [
                testBuilderClass20::class,
                true,
                [
                    [
                        'type' => 'LotosTest\Container\Request',
                        'value' => new Request(new testClass)
                    ],
                    [
                        'type' => 'LotosTest\Container\ResponseFactoryInterface',
                        'value' => new ResponseFactory
                    ]
                ]
            ],
            'два аргумента типизированных классами. один не обязательный' => [
                testBuilderClass21::class,
                true,
                [
                    [
                        'type' => 'LotosTest\Container\Request',
                        'value' => new Request(new testClass)
                    ],
                    [
                        'type' => 'LotosTest\Container\ResponseFactory',
                        'value' => new ResponseFactory
                    ]
                ]
            ],
            'два типизированных скалярных аргумента' => [
                testBuilderClass22::class,
                true,
                [
                    [
                        'type' => 'int',
                        'value' => 10,
                    ],
                    [
                        'type' => 'array',
                        'value' => []
                    ]
                ]
            ],
            'два типизированных скалярных аргумента' => [
                testBuilderClass23::class,
                true,
                [
                    [
                        'type' => 'array',
                        'value' => [1, 2, 3]
                    ],
                    [
                        'type' => 'string',
                        'value' => 'wtf'
                    ]
                ]
            ],
            'два типизированных скалярных аргумента. оба необязательные' => [
                testBuilderClass24::class,
                true,
                [
                    [
                        'type' => 'array',
                        'value' => null
                    ],
                    [
                        'type' => 'string',
                        'value' => null
                    ]
                ]
            ],
            'один типизированный скалярный аргумент и один аргумент типизированный интерфейсом' => [
                testBuilderClass25::class,
                true,
                [
                    [
                        'type' => 'array',
                        'value' => null
                    ],
                    [
                        'type' => 'string',
                        'value' => null
                    ]
                ]
            ]
        ];
    }

    /**
     * @test
     * @requires PHP >= 8.0
     * @covers Repository::saveClass
     * @covers Repository::addParam
     * @covers Repository::addTypedParam
     **/
    public function initBuilder() : BuilderInterface
    {
        $repository = RepositoryFactory::createRepository(
            CollectionFactory::createCollection()
        );
        $this->assertInstanceOf(
            RepositoryInterface::class,
            $repository,
            'Не удалось создать репозиторий'
        );

        $repository->saveClass(testBuilderClass::class);
        $repository->saveClass(testBuilderClass2::class);
        $repository->saveClass(RequestFactory::class);
        $repository->saveClass(ResponseFactory::class);
        $repository->saveClass(Request::class);
        $repository->getByClass(RequestFactory::class)
            ->addInterface(RequestFactoryInterface::class);
        $repository->getByClass(ResponseFactory::class)
            ->addInterface(ResponseFactoryInterface::class);
        $repository->getByClass(Request::class)
            ->addInterface(RequestInterface::class);
        $repository->saveClass(testBuilderClass3::class);
        $repository->saveClass(testBuilderClass4::class);
        $repository->addParam('__construct', new ArgumentEntity(name: 'data', type: 'mixed', value:[]));
        $repository->saveClass(testBuilderClass5::class);
        $repository->addParam('__construct', new ArgumentEntity(name: 'data2', type: 'mixed', value: 10));
        $repository->addParam('__construct', new ArgumentEntity(name: 'data1', type: 'mixed', value: 'foo'));
        $repository->saveClass(testBuilderClass6::class);
        $repository->addParam('__construct', new ArgumentEntity(name: 'data1', type: 'mixed', value: 100));
        $repository->saveClass(testBuilderClass7::class);
        $repository->saveClass(testBuilderClass8::class);
        $repository->addParam('__construct', new ArgumentEntity(name: 'data2', type: 'mixed', value: 1000));
        $repository->addParam('__construct', new ArgumentEntity(name: 'data1', type: 'mixed', value: 'foo'));
        $repository->saveClass(testBuilderClass9::class);
        $repository->saveClass(testBuilderClass10::class);
        $repository->addParam('__construct', new ArgumentEntity(name: 'data1', type: 'mixed', value: 1001));
        $repository->addParam('__construct', new ArgumentEntity(name: 'data2', type: 'mixed', value: 1010));
        $repository->saveClass(testBuilderClass11::class);
        $repository->saveClass(testBuilderClass12::class);
        $repository->addParam('__construct', new ArgumentEntity(name: 'data1', type: 'mixed', value: 1100));
        $repository->addParam('__construct', new ArgumentEntity(name: 'data2', type: 'mixed', value: 1101));
        $repository->saveClass(testBuilderClass13::class);
        $repository->addParam('__construct', new ArgumentEntity(name: 'intTest', type: 'int', value: 1111));
        $repository->saveClass(testBuilderClass14::class);
        $repository->saveClass(testBuilderClass15::class);
        $repository->addParam('__construct', new ArgumentEntity(name: 'nullableIntTest', type: 'int', value: 1010));
        $repository->saveClass(testBuilderClass16::class);
        $repository->addParam('__construct', new ArgumentEntity(name: 'requiredNullableIntTest', type: 'int', value: null));
        $repository->saveClass(testBuilderClass17::class);
        $repository->saveClass(testBuilderClass18::class);
        $repository->addParam('__construct', new ArgumentEntity(name: 'intTestWithDefault', type: 'int', value: 1010));
        $repository->saveClass(testBuilderClass19::class);
        $repository->saveClass(testBuilderClass20::class);
        $repository->saveClass(testBuilderClass21::class);
        $repository->saveClass(testBuilderClass22::class);
        $repository->addParam('__construct', new ArgumentEntity(name: 'foo', type: 'int', value: 10));
        $repository->addParam('__construct', new ArgumentEntity(name: 'bar', type: 'array', value: []));
        $repository->saveClass(testBuilderClass23::class);
        $repository->addParam('__construct', new ArgumentEntity(name: 'foo', type: 'array', value: [1, 2, 3]));
        $repository->addParam('__construct', new ArgumentEntity(name: 'bar', type: 'string', value: 'wtf'));
        $repository->saveClass(testBuilderClass24::class);
        $repository->saveClass(testBuilderClass25::class);


        $builder = BuilderFactory::createBuilder(
            collection: CollectionFactory::createCollection(),
            repository: $repository
        );

        $this->assertInstanceOf(
            Builder::class,
            $builder,
            'Не удалось создать билдер'
        );
        return $builder;
    }
}
