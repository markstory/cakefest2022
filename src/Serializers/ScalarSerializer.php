<?php
declare(strict_types=1);

namespace App\Serializers;

use App\Service\SerializerInterface;

class ScalarSerializer implements SerializerInterface
{
    public function prepare(array $value, array $context)
    {
        // Unused.
    }

    public function parse(array $data, array $options)
    {
        assert(false, 'Should not reach here.');
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
