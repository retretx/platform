<?php

namespace Rrmode\Platform\Bootstrap\Builders;

use Interop\Container\ContainerInterface;
use Rrmode\Platform\Bootstrap\Exceptions\ContainerBuilderException;
use Rrmode\Platform\Abstractions\AbstractAdvancedContainerCapabilities;

class ContainerBuilder
{
    /**
     * @throws ContainerBuilderException
     */
    public function __construct(
        private ContainerInterface|AbstractAdvancedContainerCapabilities|null $container = null,
        private bool $ensureAdvancedCapabilities = true,
    ){
        $this->container ??= $this->autodiscovery();

        if (
            $this->ensureAdvancedCapabilities &&
            !($this->container instanceof AbstractAdvancedContainerCapabilities)
        ) {
            throw new ContainerBuilderException(
                sprintf(
                    'Container [%s] must implement advanced capabilities',
                    get_class($this->container)
                )
            );
        }
    }

    private function autodiscovery(): ?AbstractAdvancedContainerCapabilities
    {
        if (class_exists(\League\Container\Container::class)) {
            return new \Rrmode\Platform\Bootstrap\Containers\LeagueContainer();
        }

        if (class_exists(\Illuminate\Container\Container::class)) {
            return new \Rrmode\Platform\Bootstrap\Containers\IlluminateContainer();
        }

        return null;
    }

    public function buildContainer(): ContainerInterface|AbstractAdvancedContainerCapabilities
    {
        return $this->container;
    }
}