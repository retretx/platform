<?php

namespace Rrmode\Platform\Foundation;

use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

trait Reflected
{
    public function this(): ReflectionClass
    {
        return new ReflectionClass($this);
    }

    public function getClassMethods(): array
    {
        return $this->this()
            ->getMethods();
    }

    public function thisMethodName(int $level = 1): string
    {
        return debug_backtrace()[$level]['function'];
    }

    /**
     * @throws ReflectionException
     */
    public function thisMethodReflection(int $level = 1): ReflectionMethod
    {
        return new ReflectionMethod($this, $this->thisMethodName($level + 1));
    }

    /**
     * @throws ReflectionException
     */
    public function thisMethodParameters(): array
    {
        return $this->thisMethodReflection(2)
            ->getParameters();
    }
}
