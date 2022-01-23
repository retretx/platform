<?php

namespace Rrmode\Platform\Bootstrap\Loggers;

use Analog\Handler\File;
use Analog\Logger;
use Psr\Log\LoggerInterface;
use Rrmode\Platform\Abstractions\AbstractAdvancedLoggerCapabilities;

class AnalogLogger extends AbstractAdvancedLoggerCapabilities
{
    private Logger $logger;

    public function __construct(
        private ?string $output = null
    )
    {
        $this->logger = new Logger();

        $this->logger->handler(
            File::init($output ?? STDOUT)
        );
    }

    protected function getLoggerImpl(): LoggerInterface
    {
        return $this->logger;
    }

    public function resetState()
    {
        unset($this->logger);

        $this->__construct($this->output);
    }
}
