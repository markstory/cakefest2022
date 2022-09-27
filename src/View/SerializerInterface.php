<?php
declare(strict_types=1);

namespace App\View;

interface SerializerInterface
{
    /**
     * Hook method to prepare additional data for serialization.
     *
     * While serialize() handles converting single things into
     *
     * @param array $value List of values being prepared
     * @param array $context Additional context for generating a response.
     * @return mixed A value compatible with json_encode()
     */
    public function prepare(array $value, array $context);

    /**
     * Convert a value into the json serialized format
     *
     * @param mixed $value Application internal value.
     * @param array $context Additional context for generating a response.
     * @return mixed A value compatible with json_encode()
     */
    public function serialize($value, array $context);
}
