<?php

namespace Rrmode\Platform\Foundation;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use ReflectionException;
use Rrmode\Platform\Abstractions\AbstractAdvancedContainerCapabilities;
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
        private ContainerInterface|AbstractAdvancedContainerCapabilities $container,
    ){
        static::dispatchToContainer(
            $this->container,
            new ApplicationInitializationEvent($this->now())
        );

        $this->runInitializers($this->container);

        static::dispatchToContainer(
            $this->container,
            new ApplicationInitializedEvent($this->now())
        );

        static::logDebug($this->container, 'Application initialized');
    }

    /**
     * @throws ReflectionException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function initialize(
        ContainerInterface|AbstractAdvancedContainerCapabilities $container
    ): static
    {
        static::logDebug($container, 'Initialization started');


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

        return static::getContainer()->get($class);
    }

    public static function getContainer(): ContainerInterface|AbstractAdvancedContainerCapabilities
    {
        $app = static::$app;

        return $app->container;
    }

    public function dispatch(object $event): mixed
    {
        return static::dispatchToContainer($this->container, $event);
    }

    private static function dispatchToContainer(ContainerInterface $container, object $event)
    {
        try {
            $dispatcher = $container->get(EventDispatcherInterface::class);

            return $dispatcher->dispatch($event);
        } catch (Throwable) {
            return null;
        }
    }

    private static function logDebug(ContainerInterface $container, string $message)
    {
        try {
            $logger = $container->get(LoggerInterface::class);

            $logger->debug($message);
        } catch (Throwable) {}
    }
}