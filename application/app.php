<?php

require dirname(__DIR__) .DIRECTORY_SEPARATOR.'vendor/autoload.php';

$app = buildApp();

$loader = new \Rrmode\Platform\Packages\PackageLoader($app);

$packages = $loader->getInstalledPackages();

