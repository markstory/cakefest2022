<?php
declare(strict_types=1);

namespace App\Serializers;

use App\Model\Entity\CalendarItem;
use App\Model\Entity\User;
use App\Service\SerializerInterface;
use App\Service\Serializers;
use Cake\ORM\Locator\LocatorAwareTrait;

class CalendarItemSerializer implements SerializerInterface
{
    use LocatorAwareTrait;

    protected $attrs = [];

    /**
     * @inheritDoc
     */
    public function prepare(array $items, array $context)
    {
        if (isset($context['include']) && in_array('user', $context['include'], true)) {
            $userIds = array_map(fn($item) => $item->user_id, $items);
            $userMap = $this->fetchTable('Users')->find()
                ->where(['Users.id IN' => array_unique($userIds)])
                ->all()
                ->indexBy('id');
            $this->attrs['users'] = $userMap->toArray();
        }
    }

    public function parse(array $data, array $options)
    {
        $table = $this->fetchTable('CalendarItems');
        if (isset($data['id'])) {
            $item = $table->findById($data['id'])->firstOrFail();
        } else {
            $item = $table->newEmptyEntity();
        }
        $item = $table->patchEntity($item, $data, $options);
        if (isset($data['user'])) {
            // Should probably track depth here so that loops can't be created.
            // An alternate solution would be to have a UserIncludeSerializer that
            // wouldn't recurse.
            $user = Serializers::get(User::class)->parse($data['user'], $options);
            $item->user = $user;
            $item->user_id = $user->id;
        }

        return $item;
    }

    /**
     * @inheritDoc
     */
    public function serialize($item, array $context)
    {
        assert($item instanceof CalendarItem);

        $data = [
            'id' => (string)$item->id,
            'title' => $item->title,
            'description' => $item->description,
            'startTime' => $item->start_time,
            'endTime' => $item->end_time,
        ];

        if (isset($this->attrs['users'][$item->user_id])) {
            $user = $this->attrs['users'][$item->user_id];
            $data['user'] = Serializers::get(User::class)->serialize($user, $context);
        }

        return $data;
    }
}
