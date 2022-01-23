<?php

namespace Rrmode\Platform\Foundation;

use Closure;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionClass;
use ReflectionException;
use Rrmode\Platform\Abstractions\AbstractAdvancedContainerCapabilities;
use function str_starts_with;

trait Initialization
{
    use ContainerCalls;
    use Reflected;

    static array $initializers = [];

    public function loadInitializers(): array
    {
        foreach ($this->getClassMethods() as $method) {
            $name = $method->getName();

            if ($name !== 'initialize' && str_starts_with($name, 'initialize')) {
                static::$initializers[$name] = $this->$name(...);
            }
        }

        return static::$initializers;
    }

    public static function addInitializer(string $name, Closure $initializer): void
    {
        static::$initializers[$name] = $initializer;
    }

    public function dropInitializers(): void
    {
        static::$initializers = [];
    }

    /**
     * @throws ReflectionException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function runInitializers(ContainerInterface|AbstractAdvancedContainerCapabilities $container): void
    {
        foreach ($this->loadInitializers() as $initializer) {
            $this->method($initializer, $container);
        }
    }
}
