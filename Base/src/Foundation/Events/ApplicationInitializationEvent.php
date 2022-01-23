<?php

namespace Rrmode\Platform\Foundation\Events;

use DateTimeInterface;

class ApplicationInitializationEvent
{
    public function __construct(
        private DateTimeInterface $initializationStartedAt
    ){}

    public function time(): DateTimeInterface
    {
        return $this->initializationStartedAt;
    }
}
