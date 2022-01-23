<?php

namespace Rrmode\Platform\Abstractions;

use ArrayAccess;
use Closure;
use Psr\Container\ContainerInterface;
use RuntimeException;

abstract class AbstractAdvancedContainerCapabilities implements ContainerInterface, ArrayAccess, ResetObjectStateInterface
{
    /**
     * @template T
     * @param class-string<T>|string $id
     * @return T
     */
    abstract public function get(string $id);

    abstract public function has(string $id): bool;

    abstract public function add(string $id, Closure $concrete);

    abstract public function addShared(string $id, Closure $concrete);

    public function clear(string $id)
    {
        throw new RuntimeException(
            sprintf("Container [%s] not supporting unbind operation", get_class($this))
        );
    }

    public function offsetExists(mixed $offset): bool
    {
        return $this->has($offset);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->get($offset);
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->add($offset, $value);
    }

    public function offsetUnset(mixed $offset): void
    {
        $this->clear($offset);
    }
}
