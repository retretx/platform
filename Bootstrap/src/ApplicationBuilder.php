<?php

namespace Rrmode\Platform\Bootstrap;

use Interop\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Rrmode\Platform\Abstractions\AbstractAdvancedContainerCapabilities;
use Rrmode\Platform\Abstractions\AbstractAdvancedDispatcherCapabilities;
use Rrmode\Platform\Abstractions\AbstractAdvancedLoggerCapabilities;
use Rrmode\Platform\Bootstrap\Builders\ContainerBuilder;
use Rrmode\Platform\Bootstrap\Builders\DispatcherBuilder;
use Rrmode\Platform\Bootstrap\Builders\LoggerBuilder;
use Rrmode\Platform\Bootstrap\Exceptions\ApplicationBuilderException;
use Rrmode\Platform\Foundation\Application;
use Throwable;

class ApplicationBuilder
{
    private ContainerInterface|AbstractAdvancedContainerCapabilities $container;
    private LoggerInterface|AbstractAdvancedLoggerCapabilities $logger;
    private EventDispatcherInterface|AbstractAdvancedDispatcherCapabilities $dispatcher;

    public function __construct(
        private ContainerBuilder $containerBuilder,
        private LoggerBuilder $loggerBuilder,
        private DispatcherBuilder $dispatcherBuilder,
    ){
        $this->container = $this->containerBuilder->buildContainer();
        $this->logger = $this->loggerBuilder->buildLogger();
        $this->dispatcher = $this->dispatcherBuilder->buildDispatcher();
    }

    public function setContainer(ContainerInterface|AbstractAdvancedContainerCapabilities $container): static
    {
        $this->container = $container;

        return $this;
    }

    public function setLogger(LoggerInterface|AbstractAdvancedLoggerCapabilities $logger): static
    {
        $this->logger = $logger;

        return $this;
    }

    public function setDispatcher(EventDispatcherInterface|AbstractAdvancedDispatcherCapabilities $dispatcher): static
    {
        $this->dispatcher = $dispatcher;

        return $this;
    }

    /**
     * @throws ApplicationBuilderException
     */
    public function buildApplication(): Application
    {
        try {
            $this->container->add(LoggerInterface::class, fn () => $this->logger);

            if ($this->logger instanceof AbstractAdvancedLoggerCapabilities) {
                $this->container->add(AbstractAdvancedLoggerCapabilities::class, fn () => $this->logger);
            }

            $this->container->addShared(EventDispatcherInterface::class, fn () => $this->dispatcher);

            if ($this->dispatcher instanceof AbstractAdvancedDispatcherCapabilities) {
                $this->container->addShared(AbstractAdvancedDispatcherCapabilities::class, fn () => $this->dispatcher);
            }

            return Application::initialize($this->container);
        } catch (Throwable $e) {
            throw new ApplicationBuilderException(
                sprintf(
                    'An error has occurred while building application: %s',
                    $e->getMessage()
                )
            );
        }
    }
}