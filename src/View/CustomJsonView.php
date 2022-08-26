<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     3.0.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\View;

use App\Service\Serializers;
use Cake\Core\Configure;
use Cake\View\SerializedView;
use RuntimeException;

/**
 * Application View
 *
 * Your application's default view class
 *
 * @link https://book.cakephp.org/4/en/views.html#the-app-view
 */
class CustomJsonView extends SerializedView
{
    public static function contentType(): string
    {
        return 'application/vnd.app+json';
    }

    protected function _serialize($serialize): string
    {
        $output = [];
        $data = $this->dataToSerialize($serialize);
        $context = $this->getConfig('context') ?? [];
        $serializer = $this->getConfig('serializer');
        if ($serializer && !($serializer instanceof SerializerInterface)) {
            throw new RuntimeException("Invalid serializer type of {$serializer}");
        }

        foreach ($data as $key => $value) {
            if (is_array($value) && isset($value[0])) {
                $first = $value[0];
                $serializer ??= Serializers::get(get_debug_type($first));

                $serializer->prepare($value, $context);
                foreach ($value as $index => $item) {
                    $output[$key][$index] = $serializer->serialize($item, $context);
                }

                continue;
            }

            $serializer ??= Serializers::get(get_debug_type($value));
            $serializer->prepare([$value]);
            $output[$key] = $serializer->serialize($value, $context);
        }

        return $this->jsonEncode($output);
    }

    protected function jsonEncode($data)
    {
        $jsonOptions = $this->getConfig('jsonOptions');
        if ($jsonOptions === null) {
            $jsonOptions = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_PARTIAL_OUTPUT_ON_ERROR;
        } elseif ($jsonOptions === false) {
            $jsonOptions = 0;
        }

        if (Configure::read('debug')) {
            $jsonOptions |= JSON_PRETTY_PRINT;
        }

        if (defined('JSON_THROW_ON_ERROR')) {
            $jsonOptions |= JSON_THROW_ON_ERROR;
        }

        $return = json_encode($data, $jsonOptions);
        if ($return === false) {
            throw new RuntimeException(json_last_error_msg(), json_last_error());
        }

        return $return;
    }

    protected function dataToSerialize(array $serialize)
    {
        $out = [];
        foreach ($serialize as $varName) {
            $out[$varName] = $this->viewVars[$varName] ?? null;
        }

        return $out;
    }
}
