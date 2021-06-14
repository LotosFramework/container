# Container

## Установка
```bash
composer require lotos/container
```

## Установка зависимостей
```php
$container = new Lotos\Container\Container;

$container->saveClass(Lotos\Http\ServerRequestFactory::class)
    ->forInterface(Psr\Http\Message\ServerRequestFactoryInterface::class)
    ->withAlias('serverRequestFactory');

$container->saveClass(Lotos\Http\Strategies\JsonStrategy::class)
    ->forInterface(Lotos\Http\StrategyInterface::class)
    ->withPriority(1)
    ->withAlias('jsonStrategy');

$container->saveClass(Lotos\Http\Strategies\HtmlStrategy::class)
    ->forInterface(Lotos\Http\StrategyInterface::class)
    ->withAlias('htmlStrategy');
```

## Получение объекта из контейнера
```php
$router = $container->get('router');
```

## Получение результата работы метода объекта, хранящегося в контейнере
```php
$container->saveClass(Lotos\Http\ServerRequest\ServerRequest::class)
    ->forInterface(Psr\Http\Message\ServerRequestInterface::class)
    ->setInstance(
        $container->call('serverRequestFactory', 'fromGlobals')
    );
```

## Инъекции зависимостей

### ВНИМАНИЕ!
**В текущей версии была удалена возможность устанавливать инъекции в свойства объектов, например:**

```php
class Example
{
    /**
     * @var Psr\Http\Message\RequestInterface
     * */
    private RequestInterface $request;
}
```
**теперь работать не будет и нужно явно указать в какой медот нужно внедрить инъекцию:**

```php

use Psr\Http\Message\{
    RequestInterface,
    ResponseInterface,
    ResponseFactoryInterface
};

class User
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory
    )
    {}

    public function create(RequestInterface $request) : ResponseInterface
    {
        //...
    }

}
```
