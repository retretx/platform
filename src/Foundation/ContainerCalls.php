<?php

namespace Rrmode\Platform\Foundation;

use League\Container\Exception\NotFoundException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;
use ReflectionFunction;
use ReflectionIntersectionType;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionType;

trait ContainerCalls
{
    /**
     * @param callable $method
     * @param array<ReflectionParameter> $reflectionParameters
     * @param ContainerInterface $container
     * @param array $userParameters
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function resolvedCall(
        callable $method,
        array $reflectionParameters,
        ContainerInterface $container,
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

        return $method(...$parameters);
    }

    /**
     * @param string $method
     * @param array $parameters
     * @param ContainerInterface $container
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     */
    public function method(
        string $method,
        ContainerInterface $container,
        array $parameters = [],
    ): mixed
    {
        $reflection = new ReflectionMethod($this, $method);

        return $this->resolvedCall(
            $method,
            $reflection->getParameters(),
            $container,
            $parameters
        );
    }

    /**
     * @param string $function
     * @param ContainerInterface $container
     * @param array $parameters
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     */
    public function func(
        string $function,
        ContainerInterface $container,
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
        ContainerInterface $container
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