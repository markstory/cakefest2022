<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\AppController;
use App\Service\CalendarService;

class CalendarItemsController extends AppController
{
    public function index(CalendarService $calendars)
    {
        $this->log('to the logs!');
        $this->set('calendars', $calendars->getCalendarList());
        $this->viewBuilder()->setOption('serialize', ['calendars']);
    }
}
