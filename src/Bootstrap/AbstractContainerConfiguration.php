<?php

namespace Rrmode\Platform\Bootstrap;

use Closure;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Rrmode\Platform\Foundation\Environment;
use Rrmode\Platform\Foundation\Initialization;
use Throwable;

abstract class AbstractContainerConfiguration
{
    use Initialization;
    use Environment;

    abstract public function add(string $abstract, Closure $concrete);

    abstract public function addSingleton(string $abstract, Closure $concrete);

    abstract protected function getContainerImplementation(): ContainerInterface;

    abstract protected function getLoggerImplementation(): ?LoggerInterface;

    abstract protected function getDispatcherImplementation(): ?EventDispatcherInterface;

    /**
     * @template T
     * @param class-string<T> $interface
     * @param ContainerInterface $container
     * @return T
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function instance(ContainerInterface $container, string $interface)
    {
        return $container->get($interface);
    }

    public function initializeContainer(): void
    {
        $container = $this->getContainerImplementation();

        if(!$container->has(ContainerInterface::class)) {
            $this->addSingleton(ContainerInterface::class, $this->getContainerImplementation(...));
        }
    }

    public function initializeLogger(ContainerInterface $container): void
    {
        $logger = $this->getLoggerImplementation();

        if ($logger instanceof LoggerInterface) {
            try {
                $this->add(LoggerInterface::class, $this->getLoggerImplementation(...));

                $containerLogger = $this->instance($container, LoggerInterface::class);

                $containerLogger->debug(
                    sprintf("PSR-3 compatible logger registered (%s)", get_class($logger))
                );

            } catch (Throwable) {}
        }
    }

    public function initializeDispatcher(ContainerInterface $container): void
    {
        $dispatcher = $this->getDispatcherImplementation();

        if (!($dispatcher instanceof EventDispatcherInterface)) {
            return;
        }

        try {
            $this->addSingleton(EventDispatcherInterface::class, $this->getDispatcherImplementation(...));

            $containerLogger = $this->instance($container, LoggerInterface::class);

            $containerLogger->debug(
                sprintf("PSR-14 compatible dispatcher registered (%s)", get_class($dispatcher)));

            $containerDispatcher = $this->instance($container, EventDispatcherInterface::class);

            $containerDispatcher->dispatch(
                new DispatcherRegisteredEvent($this->now())
            );

        } catch (Throwable) {
            return;
        }
    }

    public function initializeContainerConfiguration(ContainerInterface $container): void
    {
        $this->addSingleton(__CLASS__, fn() => $this);

        try {
            $containerLogger = $this->instance($container, LoggerInterface::class);

            $containerLogger->debug(
                sprintf("Container configuration registered (%s)", get_class($this))
            );

            $containerDispatcher = $this->instance($container, EventDispatcherInterface::class);

            $containerDispatcher->dispatch(
                new ContainerConfigurationLoadedEvent($this->now())
            );
        } catch (Throwable) {}
    }
}