<?php

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Rrmode\Platform\Container\AbstractAdvancedContainerCapabilities;
use Rrmode\Platform\Foundation\Application;

/**
 * @throws ReflectionException
 * @throws ContainerExceptionInterface
 * @throws NotFoundExceptionInterface
 */
function buildApp(
    ContainerInterface|AbstractAdvancedContainerCapabilities|null $container = null
): Application
{
    $container ??= containerDiscovery();

    return Application::initialize($container);
}

function containerDiscovery(): AbstractAdvancedContainerCapabilities
{
    if (class_exists(League\Container\Container::class)) {
        return new \Rrmode\Platform\Bootstrap\Containers\LeagueContainer();
    }

    if (class_exists(\Illuminate\Container\Container::class)) {
        return new \Rrmode\Platform\Bootstrap\Containers\IlluminateContainer();
    }

    throw new RuntimeException("No container");
}