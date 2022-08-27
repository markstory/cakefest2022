<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use Cake\I18n\FrozenTime;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\CalendarItemsController Test Case
 *
 * @uses \App\Controller\CalendarItemsController
 */
class CalendarItemsControllerTest extends TestCase
{
    use LocatorAwareTrait;
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

    protected $item;

    protected function setUp(): void
    {
        parent::setUp();
        $users = $this->fetchTable('Users');
        $user = $users->newEntity(['username' => 'mark', 'password' => 'hunter12']);
        $users->saveOrFail($user);

        $calendarItems = $this->fetchTable('CalendarItems');
        $one = $calendarItems->newEntity([
            'user_id' => $user->id, 
            'title' => 'Cakefest day 1',
            'description' => 'first day',
            'start_time' => FrozenTime::parse('2022-09-29 09:00:00'),
            'end_time' => FrozenTime::parse('2022-09-29 17:00:00'),
        ]);
        $calendarItems->saveOrFail($one);
        $this->item = $one;

        $two = $calendarItems->newEntity([
            'user_id' => $user->id, 
            'title' => 'Cakefest day 2',
            'description' => 'second day',
            'start_time' => FrozenTime::parse('2022-09-30 09:00:00'),
            'end_time' => FrozenTime::parse('2022-09-30 17:00:00'),
        ]);
        $calendarItems->saveOrFail($two);
    }

    public function testIndexSerialization(): void
    {
        $this->configRequest(['headers' => ['Accept' => 'application/vnd.app+json']]);
        $this->get('/calendar-items');
        $this->assertResponseOk();
        $body = json_decode($this->_response->getBody() . '', true);
        $this->assertCount(2, $body['calendarItems']);

        $first = $body['calendarItems'][0];
        $this->assertEquals('Cakefest day 1', $first['title']);
        $this->assertEquals('2022-09-29T09:00:00+00:00', $first['startTime']);
        $this->assertArrayNotHasKey('user', $first);
    }

    public function testIndexIncludeUser(): void
    {
        $this->configRequest(['headers' => ['Accept' => 'application/vnd.app+json']]);
        $this->get('/calendar-items?include=user');
        $this->assertResponseOk();
        $body = json_decode($this->_response->getBody() . '', true);
        $this->assertCount(2, $body['calendarItems']);

        $first = $body['calendarItems'][0];
        $this->assertEquals('Cakefest day 1', $first['title']);
        $this->assertArrayHasKey('user', $first);
        $this->assertEquals('mark', $first['user']['username']);
        $this->assertArrayNotHasKey('password', $first['user']);
    }

    public function testUpdateSimple(): void
    {
        $this->configRequest(['headers' => ['Accept' => 'application/vnd.app+json']]);
        $this->enableCsrfToken();
        $this->post("/calendar-items/{$this->item->id}", [
            'title' => 'Updated!',
            'description' => 'Updated!',
        ]);
        $this->assertResponseOk();
        $item = $this->fetchTable('CalendarItems')->get($this->item->id);
        $this->assertEquals('Updated!', $item->title);
        $this->assertEquals('Updated!', $item->description);
    }

    public function testUpdateReplaceUser(): void
    {
        $users = $this->fetchTable('Users');
        $sally = $users->newEntity(['username' => 'sally', 'password' => 'hunter12']);
        $users->saveOrFail($sally);

        $this->configRequest(['headers' => ['Accept' => 'application/vnd.app+json']]);
        $this->enableCsrfToken();
        $this->post("/calendar-items/{$this->item->id}", [
            'title' => 'Updated!',
            'description' => 'Updated!',
            'user' => ['username' => $sally->username, 'id' => $sally->id],
        ]);
        $this->assertResponseOk();
        $item = $this->fetchTable('CalendarItems')->get($this->item->id, ['contain' => 'Users']);
        $this->assertEquals('Updated!', $item->title);
        $this->assertEquals('Updated!', $item->description);
        $this->assertEquals($sally->id, $item->user->id);
    }
}
