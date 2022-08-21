<?php
declare(strict_types=1);

namespace App\Serializers;

use App\View\SerializerInterface;

class ScalarSerializer implements SerializerInterface
{
    public function prepare(array $value, array $context)
    {
        // Unused.
    }

    /**
     * @inheritDoc
     */
    public function serialize($value, array $context)
    {
        $type = get_debug_type($value);

        return match ($type) {
            'string' => (string)$value,
            'int' => (int)$value,
            'float' => (float)$value,
            'null' => null,
            'bool' => (bool)$value,
        };
    }
}
