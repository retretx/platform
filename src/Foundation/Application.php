<?php

namespace Rrmode\Platform\Foundation;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use ReflectionException;
use Rrmode\Platform\Foundation\Events\ApplicationInitializationEvent;
use Rrmode\Platform\Foundation\Events\ApplicationInitializedEvent;
use RuntimeException;
use Throwable;

class Application implements EventDispatcherInterface
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
        $this->dispatch(
            new ApplicationInitializationEvent($this->now())
        );

        $this->runInitializers($this->container);

        $this->dispatch(
            new ApplicationInitializedEvent($this->now())
        );
    }

    /**
     * @throws ReflectionException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function initialize(
        ContainerInterface $container
    ): static
    {
        return static::$app = new static($container);
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

    public function dispatch(object $event): mixed
    {
        try {
            $dispatcher = static::make(EventDispatcherInterface::class);

            return $dispatcher->dispatch($event);
        } catch (Throwable) {
            return null;
        }
    }
}