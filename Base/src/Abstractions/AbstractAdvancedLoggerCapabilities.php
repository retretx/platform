<?php

namespace Rrmode\Platform\Abstractions;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

abstract class AbstractAdvancedLoggerCapabilities implements LoggerInterface, ResetObjectStateInterface
{
    protected const DEFAULT_CHANNEL = 'Application';

    protected const DEFAULT_LEVEL = LogLevel::INFO;

    abstract protected function getLoggerImpl(): LoggerInterface;

    public function emergency($message, array $context = array())
    {
        $this->getLoggerImpl()->emergency($message, $context);
    }

    public function alert($message, array $context = array())
    {
        $this->getLoggerImpl()->alert($message, $context);
    }

    public function critical($message, array $context = array())
    {
        $this->getLoggerImpl()->critical($message, $context);
    }

    public function error($message, array $context = array())
    {
        $this->getLoggerImpl()->error($message, $context);
    }

    public function warning($message, array $context = array())
    {
        $this->getLoggerImpl()->warning($message, $context);
    }

    public function notice($message, array $context = array())
    {
        $this->getLoggerImpl()->notice($message, $context);
    }

    public function info($message, array $context = array())
    {
        $this->getLoggerImpl()->info($message, $context);
    }

    public function debug($message, array $context = array())
    {
        $this->getLoggerImpl()->debug($message, $context);
    }

    public function log($level, $message, array $context = array())
    {
        $this->getLoggerImpl()->log($level, $message, $context);
    }
}