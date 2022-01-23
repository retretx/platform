<?php

namespace Rrmode\Platform\Packages;

use Composer\InstalledVersions;
use Rrmode\Platform\Foundation\Application;

class PackageLoader
{
    public function __construct(
        private Application $app
    )
    {

    }

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
                $mappedPackages[] = new Package(
                    $packageName,
                    $rawPackageData['install_path'],
                    $rawPackageData['dev_requirement'],
                    $rawPackageData['version'],
                    $rawPackageData['pretty_version'],
                );
            }
        );

        return $mappedPackages;
    }

    public function getRootComposerChildPackages(): array
    {
        return $this->getRootComposerPackages()['versions'] ?? [];
    }

    public function getRootComposerPackages(): array
    {
        return current($this->getInstalledComposerPackages()) ?: [];
    }

    public function getInstalledComposerPackages(): array
    {
        return InstalledVersions::getAllRawData();
    }
}
