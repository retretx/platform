<?php

namespace Rrmode\Platform\Bootstrap\Builders;

use Psr\Log\LoggerInterface;
use Rrmode\Platform\Abstractions\AbstractAdvancedLoggerCapabilities;
use Rrmode\Platform\Bootstrap\Exceptions\LoggerBuilderException;
use Rrmode\Platform\Bootstrap\Loggers\AnalogLogger;
use Rrmode\Platform\Bootstrap\Loggers\ApixLogger;
use Rrmode\Platform\Bootstrap\Loggers\ConsolidationLogger;
use Rrmode\Platform\Bootstrap\Loggers\MonologLogger;
use Rrmode\Platform\Bootstrap\Loggers\SimpleLogger;

class LoggerBuilder
{
    /**
     * @throws LoggerBuilderException
     */
    public function __construct(
        private LoggerInterface|AbstractAdvancedLoggerCapabilities|null $logger = null,
        private bool $ensureAdvancedCapabilities = true,
        private ?string $output = null,
        private ?string $defaultChannel = null,
        private ?string $logLevel = null,
    ){
        $this->logger ??= $this->autodiscovery(
            $this->output,
            $this->defaultChannel,
            $this->logLevel
        );

        if ($this->logger == null) {
            throw new LoggerBuilderException(
                'Application needs PSR-3 logger implementation'
            );
        }

        if (
            $this->ensureAdvancedCapabilities &&
            !($this->logger instanceof AbstractAdvancedLoggerCapabilities)
        ) {
            throw new LoggerBuilderException(
                sprintf(
                    'Logger [%s] must implement advanced capabilities',
                    get_class($this->logger)
                )
            );
        }
    }

    private function autodiscovery(
        ?string $output,
        ?string $defaultChannel,
        ?string $logLevel
    ): ?AbstractAdvancedLoggerCapabilities
    {
        if (class_exists(\Monolog\Logger::class)) {
            return new MonologLogger($output, $defaultChannel, $logLevel);
        }

        if (class_exists(\Consolidation\Log\Logger::class)) {
            return new ConsolidationLogger($output);
        }

        if (class_exists(\Apix\Log\Logger::class)) {
            return new ApixLogger($output, $logLevel);
        }

        if (class_exists(\Analog\Logger::class)) {
            return new AnalogLogger($output);
        }

        return null;
    }

    public function buildLogger(): LoggerInterface|AbstractAdvancedLoggerCapabilities
    {
        return $this->logger;
    }
}
