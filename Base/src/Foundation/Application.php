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
use Throwable;

class Application implements EventDispatcherInterface, ContainerInterface
{
    use Environment;
    use Initialization;

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

        return new static($container);
    }

    public function dispatch(object $event): mixed
    {
        return static::dispatchToContainer($this, $event);
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

    /**
     * @template T
     * @param class-string<T> $id
     * @return T
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function get(string $id)
    {
        return $this->container->get($id);
    }

    public function has(string $id): bool
    {
        return $this->container->has($id);
    }
}
