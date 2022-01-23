<?php

namespace Rrmode\Platform\Bootstrap\Containers;

use Closure;
use League\Container\Container;
use League\Container\ReflectionContainer;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Rrmode\Platform\Abstractions\AbstractAdvancedContainerCapabilities;

class LeagueContainer extends AbstractAdvancedContainerCapabilities
{
    private Container $container;

    public function __construct(){
        $this->container = new Container();
        $this->container->delegate(
            new ReflectionContainer(true)
        );
    }

    /**
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

    public function add(string $id, Closure $concrete)
    {
        return $this->container->add($id, $concrete);
    }

    public function addShared(string $id, Closure $concrete)
    {
        return $this->container->addShared($id, $concrete);
    }

    public function resetState()
    {
        unset($this->container);
        $this->__construct();
    }
}