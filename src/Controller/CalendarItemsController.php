<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\AppController;
use App\Service\CalendarService;
use App\View\CustomJsonView;
use Cake\View\JsonView;

class CalendarItemsController extends AppController
{
    public function contentTypes(): array
    {
        return [JsonView::class, CustomJsonView::class];
    }

    public function index(CalendarService $calendars)
    {
        $include = (array)$this->request->getQuery('include');

        $calendars->getCalendarList(refresh: false);
        $this->viewBuilder()->setOption('serialize', ['calendars']);
        $this->viewBuilder()->setOption('include', $include);
    }
}
