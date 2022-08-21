<?php
declare(strict_types=1);

namespace App\View;

use InvalidArgumentException;

class Serializers
{
    private static $map = [
        'int' => ScalarSerializer::class,
        'float' => ScalarSerializer::class,
        'string' => ScalarSerializer::class,
        'null' => ScalarSerializer::class,
        'bool' => ScalarSerializer::class,
    ];

    private static $built = [];

    public static function map(string $type, string $class): void
    {
        if (!is_subclass_of($class, SerializerInterface::class)) {
            throw new InvalidArgumentException('Serializers must implement interface');
        }
        unset(static::$built[$type]);
        static::$map[$type] = $class;
    }

    public static function get(string $type): SerializerInterface
    {
        if (!isset(static::$built[$type])) {
            $class = static::$map[$type] ?? ScalarSerializer::class;
            static::$built[$type] = new $class();
        }

        return static::$built[$type];
    }
}
