<?php

namespace Rrmode\Platform\Bootstrap;

use DateTimeInterface;

class DispatcherRegisteredEvent
{
    public function __construct(
        private DateTimeInterface $registeredTime
    ){}

    public function time(): DateTimeInterface
    {
        return $this->registeredTime;
    }
}