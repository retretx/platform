<?php

namespace Rrmode\Platform\Packages;

use Rrmode\Platform\Foundation\Application;
use Rrmode\Platform\Foundation\Environment;
use Rrmode\Platform\Packages\Events\PackageLoadedEvent;
use Rrmode\Platform\Packages\Events\PackageLoadingEvent;
use Throwable;

class PackageLoader
{
    use Environment;

    public function __construct(
        readonly private Application $app,
    ){}

    public function initPackage(Package $package): bool
    {
        $this->app->dispatch(
            new PackageLoadingEvent(
                $this->now(),
                $package,
            )
        );

        try {
            $entry = $package->getEntry();

            $entry->initialize(
                $this->app,
                $package,
            );

            $this->app->dispatch(
                new PackageLoadedEvent(
                    $this->now(),
                    $package
                )
            );
            return true;
        } catch (Throwable) {
            return false;
        }
    }
}