<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\CalendarItemsController Test Case
 *
 * @uses \App\Controller\CalendarItemsController
 */
class CalendarItemsControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.CalendarItems',
        'app.Users',
    ];

    public function testIndexSerialization(): void
    {
    }

    public function testUpdateSimple(): void
    {
    }

    public function testUpdateReplaceUser(): void
    {
    }
}
