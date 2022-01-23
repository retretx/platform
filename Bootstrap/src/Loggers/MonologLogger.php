<?php

namespace Rrmode\Platform\Bootstrap\Loggers;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Rrmode\Platform\Abstractions\AbstractAdvancedLoggerCapabilities;
use const STDOUT;

class MonologLogger extends AbstractAdvancedLoggerCapabilities
{
    private Logger $logger;

    public function __construct(
        ?string $output = null,
        ?string $channel = null,
        ?string $level = self::DEFAULT_LEVEL
    )
    {
        $this->logger = new Logger($channel ?? self::DEFAULT_CHANNEL);
        $this->logger->pushHandler(
            new StreamHandler(
                $output ?? STDOUT
            )
        );
    }

    protected function getLoggerImpl(): LoggerInterface
    {
        return $this->logger;
    }

    public function resetState()
    {
        $this->logger->reset();
    }
}