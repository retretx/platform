<?php

namespace Rrmode\Platform\Bootstrap\Containers;

use Closure;
use Illuminate\Contracts\Container\Container;
use Rrmode\Platform\Container\AbstractAdvancedContainerCapabilities;

class IlluminateContainer extends AbstractAdvancedContainerCapabilities
{
    private Container $container;

    public function __construct()
    {
        $this->container = new \Illuminate\Container\Container();
    }

    public function get(string $id)
    {
        return $this->container->get($id);
    }

    public function has(string $id): bool
    {
        return $this->container->has($id);
    }

    public function add(string $id, Closure $concrete)
    {
        $this->container->bind($id, $concrete, false);
    }

    public function addShared(string $id, Closure $concrete)
    {
        $this->container->bind($id, $concrete, true);
    }
}