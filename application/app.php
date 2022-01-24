<?php

use Psr\Log\LoggerInterface;
use Rrmode\Platform\Abstractions\AbstractAdvancedDispatcherCapabilities;
use Rrmode\Platform\Packages\Events\PackageDiscoveredEvent;
use Rrmode\Platform\Packages\Events\PackageLoadedEvent;
use Rrmode\Platform\Packages\Events\PackageLoadingEvent;
use Rrmode\Platform\Packages\PackageLoader;
use Rrmode\Platform\Packages\PackageRegistry;

require dirname(__DIR__) .DIRECTORY_SEPARATOR.'vendor/autoload.php';

$app = buildApp();

$dispatcher = $app->get(AbstractAdvancedDispatcherCapabilities::class);

$logger = $app->get(LoggerInterface::class);

$loader = new PackageLoader($app);

$dispatcher->listen(
    PackageDiscoveredEvent::class,
    function (PackageDiscoveredEvent $event) use ($loader, $logger) {
        $logger->info("Package {$event->package->name} discovered");
        $loader->initPackage($event->package);
    });

$dispatcher->listen(
    PackageLoadingEvent::class,
    function (PackageLoadingEvent $event) use ($logger) {
        $logger->info("Loading package {$event->package->name}");
    });


$dispatcher->listen(
    PackageLoadedEvent::class,
    function (PackageLoadedEvent $event) use ($logger) {
        $logger->info("Package {$event->package->name} loaded");
    }
);

$registry = new PackageRegistry($app);
$registry->getInstalledPackages();

