<?php

namespace Rrmode\Platform\Bootstrap\Builders;

use Psr\EventDispatcher\EventDispatcherInterface;
use Rrmode\Platform\Abstractions\AbstractAdvancedDispatcherCapabilities;
use Rrmode\Platform\Bootstrap\Dispatchers\LeagueDispatcher;
use Rrmode\Platform\Bootstrap\Exceptions\ContainerBuilderException;

class DispatcherBuilder
{
    /**
     * @throws ContainerBuilderException
     */
    public function __construct(
        private EventDispatcherInterface|AbstractAdvancedDispatcherCapabilities|null $dispatcher = null,
        private bool $ensureAdvancedCapabilities = true,
    ){
        $this->dispatcher ??= $this->autodiscovery();

        if (
            $this->ensureAdvancedCapabilities &&
            !($this->dispatcher instanceof AbstractAdvancedDispatcherCapabilities)
        ) {
            throw new ContainerBuilderException(
                sprintf(
                    'Container [%s] must implement advanced capabilities',
                    get_class($this->dispatcher)
                )
            );
        }
    }

    private function autodiscovery(): ?AbstractAdvancedDispatcherCapabilities
    {
        if (class_exists(\League\Event\EventDispatcher::class)) {
            return new LeagueDispatcher();
        }

        return null;
    }

    public function buildDispatcher(): EventDispatcherInterface|AbstractAdvancedDispatcherCapabilities
    {
        return $this->dispatcher;
    }
}
