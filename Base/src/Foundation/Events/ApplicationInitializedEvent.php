<?php

namespace Rrmode\Platform\Foundation\Events;

use DateTimeInterface;

class ApplicationInitializedEvent
{
    public function __construct(
        private DateTimeInterface $initializedAt
    ){}

    public function time(): DateTimeInterface
    {
        return $this->initializedAt;
    }
}
