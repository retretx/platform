<?php

namespace Rrmode\Platform\Foundation;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionClass;
use ReflectionException;
use function str_starts_with;

trait Initialization
{
    use ContainerCalls;

    public function getInitializers(): array
    {
        $initializers = [];

        $class = new ReflectionClass($this);

        $methods = $class->getMethods();

        foreach ($methods as $method) {
            $name = $method->getName();

            if ($name !== 'initialize' && str_starts_with($name, 'initialize')) {
                $initializers[] = $name;
            }
        }

        return $initializers;
    }

    /**
     * @throws ReflectionException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function runInitializers(ContainerInterface $container): void
    {
        foreach ($this->getInitializers() as $initializer) {
            $this->method($initializer, $container);
        }
    }
}