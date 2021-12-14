<?php

namespace Tests\Api;

use EscolaLms\Core\Tests\CreatesUsers;
use EscolaLms\Notifications\Database\Seeders\NotificationsPermissionsSeeder;
use EscolaLms\Notifications\Tests\Mocks\DifferentTestEvent;
use EscolaLms\Notifications\Tests\Mocks\TestEvent;
use EscolaLms\Notifications\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NotificationsApiTest extends TestCase
{
    use DatabaseTransactions;
    use CreatesUsers;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(NotificationsPermissionsSeeder::class);
    }

    public function test_user_can_see_his_notifications()
    {
        $student = $this->makeStudent();
        $friend = $this->makeStudent();

        event(new TestEvent($student, $friend, 'foo'));
        event(new TestEvent($friend, $student, 'foo'));

        $response = $this->actingAs($student)->json('GET', '/api/notifications/');
        $response->assertOk();
        $response->assertJsonCount(1, 'data');

        $json = $response->json();
        $notificationDb = $json['data'][0];

        $this->assertEquals($friend->getKey(), $notificationDb['data']['friend']['id']);
    }

    public function test_user_can_mark_his_notification_as_read()
    {
        $student = $this->makeStudent();
        $friend = $this->makeStudent();

        event(new TestEvent($student, $friend, 'foo'));
        event(new TestEvent($friend, $student, 'foo'));

        $notification = $student->notifications()->latest()->first();
        $friendNotification = $friend->notifications()->latest()->first();

        $this->assertNull($notification->read_at);

        $response = $this->actingAs($student)->json('GET', '/api/notifications/');
        $response->assertOk();
        $response->assertJsonCount(1, 'data');

        $response = $this->actingAs($student)->json('POST', '/api/notifications/' . $notification->getKey() . '/read');
        $response->assertOk();

        $notification->refresh();
        $this->assertNotNull($notification->read_at);

        $response = $this->actingAs($student)->json('GET', '/api/notifications/');
        $response->assertOk();
        $response->assertJsonCount(0, 'data');

        $response = $this->actingAs($student)->json('GET', '/api/notifications/', ['include_read' => true]);
        $response->assertOk();
        $response->assertJsonCount(1, 'data');

        $response = $this->actingAs($student)->json('POST', '/api/notifications/' . $friendNotification->getKey() . '/read');
        $response->assertForbidden();
    }

    public function test_user_can_not_see_others_notifications()
    {
        $student = $this->makeStudent();
        $friend = $this->makeStudent();

        event(new TestEvent($student, $friend, 'foo'));
        event(new TestEvent($friend, $student, 'foo'));

        $response = $this->actingAs($student)->json('GET', '/api/notifications/' . $friend->getKey());
        $response->assertStatus(404);

        $response = $this->actingAs($student)->json('GET', '/api/admin/notifications/' . $student->getKey());
        $response->assertStatus(401);

        $response = $this->actingAs($student)->json('GET', '/api/admin/notifications/' . $friend->getKey());
        $response->assertStatus(401);
    }

    public function test_admin_can_see_any_notifications()
    {
        $admin = $this->makeAdmin();
        $student = $this->makeStudent();
        $friend = $this->makeStudent();

        event(new TestEvent($student, $friend, 'foo'));
        event(new TestEvent($friend, $student, 'foo'));

        $response = $this->actingAs($admin, 'api')->json('GET', '/api/admin/notifications/' . $student->getKey());
        $response->assertOk();
        $response->assertJsonCount(1, 'data');

        $json = $response->json();
        $notificationDb = $json['data'][0];
        $this->assertEquals($friend->getKey(), $notificationDb['data']['friend']['id']);

        $response = $this->actingAs($admin, 'api')->json('GET', '/api/admin/notifications/' . $friend->getKey());
        $response->assertOk();
        $response->assertJsonCount(1, 'data');

        $json = $response->json();
        $notificationDb = $json['data'][0];
        $this->assertEquals($student->getKey(), $notificationDb['data']['friend']['id']);
    }

    public function test_admin_can_see_event_list()
    {
        $admin = $this->makeAdmin();
        $student = $this->makeStudent();
        $friend = $this->makeStudent();

        event(new TestEvent($student, $friend, 'foo'));
        event(new TestEvent($friend, $student, 'foo'));
        event(new DifferentTestEvent($student, 'bar'));

        $response = $this->actingAs($admin, 'api')->json('GET', '/api/admin/notifications/events');
        $response->assertOk();
        $response->assertJsonCount(2, 'data');
        $response->assertJsonFragment([
            'data' => [
                TestEvent::class,
                DifferentTestEvent::class,
            ]
        ]);
    }

    public function test_admin_can_filter_notifications_by_event()
    {
        $admin = $this->makeAdmin();
        $student = $this->makeStudent();
        $friend = $this->makeStudent();

        event(new TestEvent($student, $friend, 'foo'));
        event(new TestEvent($friend, $student, 'foo'));
        event(new DifferentTestEvent($student, 'bar'));

        $response = $this->actingAs($admin, 'api')->json('GET', '/api/admin/notifications/', [
            'event' => TestEvent::class,
        ]);
        $response->assertOk();
        $response->assertJsonCount(2, 'data');

        $response = $this->actingAs($admin, 'api')->json('GET', '/api/admin/notifications/', [
            'event' => DifferentTestEvent::class,
        ]);
        $response->assertOk();
        $response->assertJsonCount(1, 'data');

        $response = $this->actingAs($admin, 'api')->json('GET', '/api/admin/notifications/' . $student->getKey(), [
            'event' => DifferentTestEvent::class,
        ]);
        $response->assertOk();
        $response->assertJsonCount(1, 'data');

        $response = $this->actingAs($admin, 'api')->json('GET', '/api/admin/notifications/' . $student->getKey(), [
            'event' => TestEvent::class,
        ]);
        $response->assertOk();
        $response->assertJsonCount(1, 'data');

        $response = $this->actingAs($admin, 'api')->json('GET', '/api/admin/notifications/' . $friend->getKey(), [
            'event' => DifferentTestEvent::class,
        ]);
        $response->assertOk();
        $response->assertJsonCount(0, 'data');

        $response = $this->actingAs($admin, 'api')->json('GET', '/api/admin/notifications/' . $friend->getKey(), [
            'event' => TestEvent::class,
        ]);
        $response->assertOk();
        $response->assertJsonCount(1, 'data');
    }
}
