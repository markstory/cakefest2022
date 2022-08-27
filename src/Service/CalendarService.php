<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Entity\CalendarItem;
use Cake\Http\Client;
use Cake\ORM\Locator\LocatorAwareTrait;

class CalendarService
{
    use LocatorAwareTrait;

    /**
     * @var \Cake\Http\Client
     */
    private $client;

    /**
     * @var \App\Model\Table\CalendarItemsTable
     */
    private $CalendarItems;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->CalendarItems = $this->fetchTable('CalendarItems');
    }

    /**
     * @param bool $refresh
     */
    public function getCalendarList(bool $refresh = false)
    {
        if ($refresh) {
            $response = $this->client->get('https://calendar.google.com/api/1');
            $data = $response->getJson();
            // TODO insert data into calendar_items table.
        }

        return $this->CalendarItems->find()->toArray();
    }

    public function update(int $id, array $data)
    {
        $data['id'] = $id;
        $item = Serializers::get(CalendarItem::class)->parse($data, []);
        if ($item->hasErrors()) {
            throw new ValidationError('Validation failed');
        }
        $this->CalendarItems->saveOrFail($item);

        return $item;
    }
}
