<?php

namespace Rrmode\Platform\Bootstrap;

interface  ContainerConfigurationInterface
{
    public function add(string $abstract, callable $concrete);

    public function addSingleton(string $abstract, callable $concrete);
}