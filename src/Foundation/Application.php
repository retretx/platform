<?php

namespace Rrmode\Platform\Foundation;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;
use RuntimeException;

class Application
{
    use Environment;
    use Initialization;

    private static Application $app;

    /**
     * @throws ReflectionException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function __construct(
        private ContainerInterface $container,
    ){
        $this->runInitializers($this->container);
    }

    /**
     * @throws ReflectionException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function initialize(
        ContainerInterface $container
    )
    {
        static::$app = new static($container);
    }

    /**
     * @template T
     * @param class-string<T> $class
     * @return T
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws RuntimeException
     */
    public static function make(string $class = __CLASS__)
    {
        if (!isset(static::$app)) {
            throw new RuntimeException('Application not initialized');
        }

        $app = static::$app;

        if ($class === __CLASS__) {
            return $app;
        }

        return $app->container->get($class);
    }
}