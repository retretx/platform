<?php

namespace Rrmode\Platform\Foundation\Events;

use DateTimeInterface;
use Rrmode\Platform\Foundation\Application;

class ApplicationInitializedEvent
{
    public function __construct(
        readonly public DateTimeInterface $time,
        readonly public Application $application,
    ){}
}
