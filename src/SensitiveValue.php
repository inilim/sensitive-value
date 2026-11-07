<?php

declare(strict_types=1);

namespace Inilim\SensitiveValue;

final class SensitiveValue
{
    /**
     * @var \Closure():mixed|\SensitiveParameterValue
     */
    private object $object;

    /**
     * @param mixed $value
     */
    function __construct(
        #[\SensitiveParameter]
        $value
    ) {
        $this->object = \PHP_VERSION_ID >= 80200
            ? new \SensitiveParameterValue($value)
            // \Closure hidden value for var_export
            : (static fn() => $value)->bindTo(null, null);
    }

    /**
     * @return mixed
     */
    function getValue()
    {
        return $this->object instanceof \SensitiveParameterValue
            ? $this->object->getValue()
            : ($this->object)();
    }

    function __debugInfo(): array
    {
        return [];
    }

    function __sleep(): array
    {
        throw new \Exception("Serialization of 'SensitiveValue' is not allowed");
    }

    function __wakeup(): void
    {
        throw new \Exception("Unserialization of 'SensitiveValue' is not allowed");
    }
}
