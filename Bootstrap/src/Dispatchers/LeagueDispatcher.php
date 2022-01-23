<?php

namespace Rrmode\Platform\Bootstrap\Dispatchers;

use Closure;
use League\Event\EventDispatcher;
use Psr\EventDispatcher\EventDispatcherInterface;
use Rrmode\Platform\Abstractions\AbstractAdvancedDispatcherCapabilities;
use Stringable;

class LeagueDispatcher extends AbstractAdvancedDispatcherCapabilities
{
    private EventDispatcher $dispatcher;

    public function __construct()
    {
        $this->dispatcher = new EventDispatcher();
    }

    protected function getDispatcherImpl(): EventDispatcherInterface
    {
        return $this->dispatcher;
    }

    public function listen(Stringable|string $event, callable|Closure $listener)
    {
        $this->dispatcher->subscribeTo((string) $event, $listener);
    }
}