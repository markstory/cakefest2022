<?php
declare(strict_types=1);

namespace App\Serializers;

use App\Model\Entity\CalendarItem;
use App\Model\Entity\User;
use App\View\SerializerInterface;
use App\View\Serializers;
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
