<?php

namespace Rrmode\Platform\Abstractions;

use Closure;
use Psr\EventDispatcher\EventDispatcherInterface;
use Stringable;

abstract class AbstractAdvancedDispatcherCapabilities implements EventDispatcherInterface
{
    abstract protected function getDispatcherImpl(): EventDispatcherInterface;

    public function dispatch(object $event): object
    {
        return $this->getDispatcherImpl()->dispatch($event);
    }

    abstract public function listen(Stringable|string $event, callable|Closure $listener);
}