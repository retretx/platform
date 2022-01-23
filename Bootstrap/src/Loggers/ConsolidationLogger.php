<?php

namespace Rrmode\Platform\Bootstrap\Loggers;

use Consolidation\Log\Logger;
use Psr\Log\LoggerInterface;
use Rrmode\Platform\Abstractions\AbstractAdvancedLoggerCapabilities;
use Symfony\Component\Console\Output\StreamOutput;
use const STDOUT;

class ConsolidationLogger extends AbstractAdvancedLoggerCapabilities
{
    private Logger $logger;

    public function __construct(
        private ?string $output
    )
    {
        $this->logger = new Logger(
            new StreamOutput($this->output ?? STDOUT)
        );
    }

    protected function getLoggerImpl(): LoggerInterface
    {
        return $this->logger;
    }

    public function resetState()
    {
        unset ($this->logger);
        $this->__construct($this->output);
    }
}