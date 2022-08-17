<?php
declare(strict_types=1);

namespace App\Controller;

use App\Service\CalendarService;
use Cake\Controller\Controller;

class CalendarsItemsController extends Controller
{
    public function index(CalendarService $calendars)
    {
        $this->log('to the logs!');
        $this->set('calendars', $calendars->getCalendarList());
        $this->viewBuilder()->setOption('serialize', ['calendars']);
    }
}
