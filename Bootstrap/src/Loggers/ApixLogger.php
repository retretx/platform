<?php

namespace Rrmode\Platform\Bootstrap\Loggers;

use Apix\Log\Logger;
use Psr\Log\LoggerInterface;
use Rrmode\Platform\Abstractions\AbstractAdvancedLoggerCapabilities;
use const STDOUT;

class ApixLogger extends AbstractAdvancedLoggerCapabilities
{
    private Logger $logger;

    private Logger\Stream $streamLogger;

    public function __construct(
        private ?string $output = null,
        private ?string $logLevel = self::DEFAULT_LEVEL,
    )
    {
        $this->streamLogger = new Logger\Stream($output ?? STDOUT);

        $this->logger = new Logger([$this->streamLogger]);

        $this->logger->setMinLevel($this->logLevel);
    }

    protected function getLoggerImpl(): LoggerInterface
    {
        return $this->logger;
    }

    public function resetState()
    {
        $this->streamLogger->close();
        $this->logger->close();

        $this->__construct($this->output, $this->logLevel);
    }
}