<?php

use Rrmode\Platform\Bootstrap\ApplicationBuilder;
use Rrmode\Platform\Bootstrap\Builders\ContainerBuilder;
use Rrmode\Platform\Bootstrap\Builders\DispatcherBuilder;
use Rrmode\Platform\Bootstrap\Builders\LoggerBuilder;
use Rrmode\Platform\Bootstrap\Exceptions\ApplicationBuilderException;
use Rrmode\Platform\Foundation\Application;

/**
 * @throws ApplicationBuilderException
 */
function buildApp(
    ContainerBuilder $containerBuilder = new ContainerBuilder(),
    LoggerBuilder $loggerBuilder = new LoggerBuilder(),
    DispatcherBuilder $dispatcherBuilder = new DispatcherBuilder(),
): Application
{
    $appBuilder = new ApplicationBuilder(
        $containerBuilder,
        $loggerBuilder,
        $dispatcherBuilder,
    );

    return $appBuilder->buildApplication();
}
