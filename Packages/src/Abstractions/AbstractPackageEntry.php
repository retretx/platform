<?php

namespace Rrmode\Platform\Packages\Abstractions;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;
use Rrmode\Platform\Foundation\Application;
use Rrmode\Platform\Foundation\Environment;
use Rrmode\Platform\Foundation\Initialization;
use Rrmode\Platform\Packages\Events\PackageInitializationEvent;
use Rrmode\Platform\Packages\Events\PackageInitializedEvent;
use Rrmode\Platform\Packages\Package;

abstract class AbstractPackageEntry
{
    use Initialization;
    use Environment;

    /**
     * @throws ReflectionException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function initialize(Application $app, Package $package)
    {
        $app->dispatch(
            new PackageInitializationEvent(
                $this->now(),
                $package,
            )
        );

        $this->dropInitializers();
        $this->loadInitializers();
        $this->runInitializers($app);

        $app->dispatch(
            new PackageInitializedEvent(
                $this->now(),
                $package,
            )
        );
    }
}
