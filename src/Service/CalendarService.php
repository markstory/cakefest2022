<?php
declare(strict_types=1);

namespace App\Service;

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

    public function getCalendarList()
    {
        $response = $this->client->get('https://calendar.google.com/api/1');
        $data = $response->getJson();

        return $data ?? [];
    }

    public function getLocal()
    {
        return $this->CalendarItems->find()->toArray();
    }
}
