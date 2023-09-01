<?php

namespace App\Support;

use UnexpectedValueException;

class Type
{
    public static function string(mixed $value, string $default = ''): string
    {
        return self::nullableString($value, $default) ?? $default;
    }

    public static function float(mixed $value, float $default = 0): float
    {
        return self::nullableFloat($value, $default) ?? $default;
    }

    public static function int(mixed $value, int $default = 0): int
    {
        return self::nullableInt($value, $default) ?? $default;
    }

    /**
     * @param  array<array-key, mixed>  $default
     * @return array<array-key, mixed>
     */
    public static function array(mixed $value, array $default = []): array
    {
        return self::nullableArray($value, $default) ?? $default;
    }

    /**
     * @param  ?array<array-key, mixed>  $default
     * @return ?array<array-key, mixed>
     */
    public static function nullableArray(mixed $value, array $default = null): ?array
    {
        $value = value($value);

        if (is_array($value)) {
            return $value;
        }

        if (is_null($value)) {
            return $default;
        }

        if (is_object($value) && method_exists($value, 'toArray')) {
            return $value->toArray();
        }

        throw new UnexpectedValueException('Unable to convert value of type '.gettype($value).' to array.');
    }

    /**
     * @template T of object
     *
     * @param  class-string<T>  $class
     * @return T
     */
    public static function instanceOf(mixed $value, string $class): object
    {
        return self::nullableInstanceOf($value, $class)
            ?? throw new UnexpectedValueException(gettype($value).' is not an instance of '.$class.'.');
    }

    /**
     * @template T of object
     *
     * @param  class-string<T>  $class
     * @return T
     */
    public static function nullableInstanceOf(mixed $value, string $class): ?object
    {
        $value = value($value);

        if (is_null($value)) {
            return null;
        }

        if (is_object($value) && is_a($value, $class)) {
            return $value;
        }

        throw new UnexpectedValueException(gettype($value).' is not an instance of '.$class.'.');
    }

    public static function nullableString(mixed $value, string $default = null): ?string
    {
        $value = value($value);

        if (is_string($value)) {
            return $value;
        }

        if (is_null($value)) {
            return $default;
        }

        if (is_bool($value) || is_int($value) || is_float($value)) {
            return strval($value);
        }

        if (is_object($value)) {
            if (method_exists($value, '__toString')) {
                return $value->__toString();
            }

            if (method_exists($value, 'toString')) {
                return $value->toString();
            }
        }

        throw new UnexpectedValueException('Unable to convert value of type '.gettype($value).' to string.');
    }

    public static function nullableFloat(mixed $value, float $default = null): ?float
    {
        $value = value($value);

        if (is_float($value)) {
            return $value;
        }

        if (is_null($value)) {
            return $default;
        }

        if (is_bool($value) || is_int($value) || is_string($value)) {
            return floatval($value);
        }

        throw new UnexpectedValueException('Unable to convert value of type '.gettype($value).' to float.');
    }

    public static function nullableInt(mixed $value, int $default = null): ?int
    {
        $value = value($value);

        if (is_int($value)) {
            return $value;
        }

        if (is_null($value)) {
            return $default;
        }

        if (is_bool($value) || is_float($value) || is_string($value)) {
            return intval($value);
        }

        throw new UnexpectedValueException('Unable to convert value of type '.gettype($value).' to int.');
    }
}
