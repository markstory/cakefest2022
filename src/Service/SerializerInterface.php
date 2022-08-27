<?php
declare(strict_types=1);

namespace App\Service;

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

    /**
     * Convert request data into objects.
     *
     * This allows you to map request data back into the application
     * objects it was when it was serialized with `serialize()`.
     *
     * If you don't want to use this behavior leave the method empty
     * or throw an exception.
     *
     * @param array $data The request data.
     * @param array $options The options to use for parsing. This
     *    is an application specific bag of configuration data.
     * @return mixed The parsed/deserialized form of the request data.
     */
    public function parse(array $data, array $options);
}
