<?php

namespace Rrmode\Platform\Foundation;

use DateTimeImmutable;
use Psr\Container\ContainerInterface;
use Rrmode\Platform\Bootstrap\AbstractAdvancedContainerCapabilities;
use function php_sapi_name;
use const STDIN;
use const STDOUT;

trait Environment
{
    public function isStdinAvailable(): bool
    {
        return defined(STDIN);
    }

    public function isStdoutAvailable(): bool
    {
        return defined(STDOUT);
    }

    public function getSapiName(): string
    {
        return php_sapi_name();
    }

    public function runningInConsole(): bool
    {
        $sapi = $this->getSapiName();

        return $sapi === 'phpdbg' || $sapi === 'cli';
    }

    public function getVariable(string $name, mixed $default = null)
    {
        return $_ENV[$name] ?? $default;
    }

    public function now(): DateTimeImmutable
    {
        return new DateTimeImmutable();
    }

    public function isAdvancedContainerCapabilitiesDefined(
        ContainerInterface $container
    ): bool
    {
        return $container->has(AbstractAdvancedContainerCapabilities::class);
    }
}