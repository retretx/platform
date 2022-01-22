<?php

namespace Rrmode\Platform\Foundation;

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
}