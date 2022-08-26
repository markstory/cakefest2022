<?php
declare(strict_types=1);

namespace App\Serializers;

use App\Model\Entity\User;
use App\Service\SerializerInterface;
use Cake\ORM\Locator\LocatorAwareTrait;

class UserSerializer implements SerializerInterface
{
    /**
     * @inheritDoc
     */
    public function prepare(array $items, array $context)
    {
    }

    /**
     * @inheritDoc
     */
    public function serialize($item, array $context)
    {
        assert($item instanceof User);

        return [
            'id' => (string)$item->id,
            'username' => $item->username,
        ];
    }
}
