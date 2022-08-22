<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\AppController;
use App\Service\CalendarService;
use App\View\CustomJsonView;
use Cake\View\JsonView;

class CalendarItemsController extends AppController
{
    public function viewClasses(): array
    {
        return [JsonView::class, CustomJsonView::class];
    }

    public function index(CalendarService $calendars)
    {
        $include = (array)$this->request->getQuery('include');

        $items = $calendars->getCalendarList(refresh: false);
        $this->set('calendarItems', $items);
        $this->viewBuilder()->setOption('serialize', ['calendarItems']);
        $this->viewBuilder()->setOption('context', ['include' => $include]);
    }
}
