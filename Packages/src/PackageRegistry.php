<?php

namespace Rrmode\Platform\Packages;

use Composer\InstalledVersions;
use Psr\Container\ContainerInterface;
use Rrmode\Platform\Foundation\Application;
use Rrmode\Platform\Foundation\Environment;
use Rrmode\Platform\Packages\Events\PackageDiscoveredEvent;
use Rrmode\Platform\Packages\Events\PackageLoadedEvent;
use Rrmode\Platform\Packages\Events\PackageLoadingEvent;
use Throwable;

class PackageRegistry
{
    use Environment;

    public function __construct(
        readonly private Application $app,
    ){}

    /**
     * @return array<Package>
     */
    public function getInstalledPackages(): array
    {
        $platformPackages = array_filter(
            $this->getRootComposerChildPackages(),
            fn (array $rawChildPackage) =>
                isset($rawChildPackage['type']) && $rawChildPackage['type'] === 'platform-package'
        );

        $mappedPackages = [];

        array_walk(
            $platformPackages,
            function (array $rawPackageData, string $packageName) use (&$mappedPackages) {
                try {
                    $package = new Package(
                        $packageName,
                        $rawPackageData['install_path'],
                        $rawPackageData['dev_requirement'],
                        $rawPackageData['version'],
                        $rawPackageData['pretty_version'],
                    );

                    $mappedPackages[] = $package;

                    $this->app->dispatch(
                        new PackageDiscoveredEvent(
                            $this->now(),
                            $package
                        )
                    );
                } catch (Throwable) {}
            }
        );

        return $mappedPackages;
    }

    private function getRootComposerChildPackages(): array
    {
        return $this->getRootComposerPackages()['versions'] ?? [];
    }

    private function getRootComposerPackages(): array
    {
        return current($this->getInstalledComposerPackages()) ?: [];
    }

    private function getInstalledComposerPackages(): array
    {
        return InstalledVersions::getAllRawData();
    }
}
