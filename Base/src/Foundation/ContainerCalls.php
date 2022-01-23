<?php

namespace Rrmode\Platform\Foundation;

use Closure;
use League\Container\Exception\NotFoundException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;
use ReflectionFunction;
use ReflectionIntersectionType;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionType;
use Rrmode\Platform\Bootstrap\AbstractAdvancedContainerCapabilities;

trait ContainerCalls
{
    /**
     * @param Closure $function
     * @param array<ReflectionParameter> $reflectionParameters
     * @param ContainerInterface|AbstractAdvancedContainerCapabilities $container
     * @param array $userParameters
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function resolvedCall(
        Closure $function,
        array $reflectionParameters,
        ContainerInterface|AbstractAdvancedContainerCapabilities $container,
        array $userParameters = [],
    ): mixed
    {
        $resolvedParams = [];

        foreach ($reflectionParameters as $parameter) {
            if ($parameter->hasType()) {
                $type = $parameter->getType();

                $resolvedParams[$parameter->getName()] = $this->resolve($type, $container);
            }
        }

        $parameters = array_merge(
            $resolvedParams,
            $userParameters,
        );

        return $function(...$parameters);
    }

    /**
     * @param Closure $method
     * @param ContainerInterface|AbstractAdvancedContainerCapabilities $container
     * @param array $parameters
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     */
    public function method(
        Closure     $method,
        ContainerInterface|AbstractAdvancedContainerCapabilities $container,
        array              $parameters = [],
    ): mixed
    {
        $reflection = new ReflectionFunction($method);

        return $this->resolvedCall(
            $method,
            $reflection->getParameters(),
            $container,
            $parameters
        );
    }

    /**
     * @param Closure $function
     * @param ContainerInterface|AbstractAdvancedContainerCapabilities $container
     * @param array $parameters
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     */
    public function func(
        Closure $function,
        ContainerInterface|AbstractAdvancedContainerCapabilities $container,
        array $parameters = [],
    ): mixed
    {
        $reflection = new ReflectionFunction($function);

        return $this->resolvedCall(
            $function, $reflection->getParameters(),
            $container,
            $parameters
        );
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function resolve(
        ReflectionType|ReflectionNamedType|ReflectionIntersectionType $type,
        ContainerInterface|AbstractAdvancedContainerCapabilities $container
    ): object
    {
        if ($type instanceof ReflectionIntersectionType) {
            $types = $type->getTypes();

            foreach ($types as $type) {
                if ($container->has($type)) {
                    return $container->get($type);
                }
            }

            throw new NotFoundException();
        }

        return $container->get($type->getName());
    }
}