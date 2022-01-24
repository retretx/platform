<?php

namespace Rrmode\Platform\Packages\Events;

use DateTimeImmutable;
use Rrmode\Platform\Packages\Package;

class PackageLoadedEvent
{
    public function __construct(
        readonly public DateTimeImmutable $time,
        readonly public Package $package,
    ){}
}
