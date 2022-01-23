<?php

namespace Rrmode\Platform\Abstractions;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Stringable;

abstract class AbstractAdvancedLoggerCapabilities implements LoggerInterface, ResetObjectStateInterface
{
    protected const DEFAULT_CHANNEL = 'Application';

    protected const DEFAULT_LEVEL = LogLevel::INFO;

    abstract protected function getLoggerImpl(): LoggerInterface;

    public function emergency(Stringable|string $message, array $context = []): void
    {
        $this->getLoggerImpl()->emergency($message, $context);
    }

    public function alert(Stringable|string $message, array $context = []): void
    {
        $this->getLoggerImpl()->alert($message, $context);
    }

    public function critical(Stringable|string $message, array $context = []): void
    {
        $this->getLoggerImpl()->critical($message, $context);
    }

    public function error(Stringable|string $message, array $context = []): void
    {
        $this->getLoggerImpl()->error($message, $context);
    }

    public function warning(Stringable|string $message, array $context = []): void
    {
        $this->getLoggerImpl()->warning($message, $context);
    }

    public function notice(Stringable|string $message, array $context = []): void
    {
        $this->getLoggerImpl()->notice($message, $context);
    }

    public function info(Stringable|string $message, array $context = []): void
    {
        $this->getLoggerImpl()->info($message, $context);
    }

    public function debug(Stringable|string $message, array $context = []): void
    {
        $this->getLoggerImpl()->debug($message, $context);
    }

    public function log($level, Stringable|string $message, array $context = []): void
    {
        $this->getLoggerImpl()->log($level, $message, $context);
    }
}
