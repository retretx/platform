<?php

use Rrmode\Platform\Abstractions\AbstractAdvancedDispatcherCapabilities;

require dirname(__DIR__) .DIRECTORY_SEPARATOR.'vendor/autoload.php';

$app = buildApp();

$dispatcher = $app->get(AbstractAdvancedDispatcherCapabilities::class);

$dispatcher->listen(\Rrmode\Platform\Packages\Events\PackageLoadingEvent::class, function (\Rrmode\Platform\Packages\Events\PackageLoadingEvent $event) {
    echo "Loading package {$event->package->name}\n";
});

$registry = new \Rrmode\Platform\Packages\PackageRegistry($app);

$loader = new \Rrmode\Platform\Packages\PackageLoader($app);

$packages = $registry->getInstalledPackages();

foreach ($packages as $package) {
    $loader->initPackage($package);
}

