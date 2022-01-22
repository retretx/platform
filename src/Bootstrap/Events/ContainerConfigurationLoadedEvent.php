<?php

namespace Rrmode\Platform\Bootstrap;

use DateTimeInterface;

class ContainerConfigurationLoadedEvent
{
    public function __construct(
        private DateTimeInterface $loadedTime
    ){}

    public function time(): DateTimeInterface
    {
        return $this->loadedTime;
    }
}