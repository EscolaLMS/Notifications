<?php

namespace Tests\Api;

use EscolaLms\Core\Tests\CreatesUsers;
use EscolaLms\Notifications\Database\Seeders\NotificationsPermissionsSeeder;
use EscolaLms\Notifications\Facades\EscolaLmsNotifications;
use EscolaLms\Notifications\Tests\Mocks\TestNotificationWithVariables;
use EscolaLms\Notifications\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NotificationsApiTest extends TestCase
{
    use DatabaseTransactions;
    use CreatesUsers;

    protected function setUp(): void
    {
        parent::setUp();

        EscolaLmsNotifications::registerNotification(TestNotificationWithVariables::class);
        EscolaLmsNotifications::createDefaultTemplates();

        $this->seed(NotificationsPermissionsSeeder::class);
    }

    public function test_user_can_see_his_notifications()
    {
        $student = $this->makeStudent();
        $friend = $this->makeStudent();

        $notification = new TestNotificationWithVariables($friend);
        $student->notifyNow($notification, ['database']);

        $response = $this->actingAs($student)->json('GET', '/api/notifications/');
        $response->assertOk();

        $json = $response->json();
        $notificationDb = $json['data'][0];

        $this->assertEquals($notification->toArray($student, 'database'), $notificationDb['data']);
    }

    public function test_admin_can_see_any_notifications()
    {
        $admin = $this->makeAdmin();
        $student = $this->makeStudent();
        $friend = $this->makeStudent();

        $notification = new TestNotificationWithVariables($friend);
        $student->notifyNow($notification, ['database']);
        $notification2 = new TestNotificationWithVariables($student);
        $friend->notifyNow($notification2, ['database']);

        $response = $this->actingAs($admin)->json('GET', '/api/notifications/' . $student->getKey());
        $response->assertOk();
        $response->assertJsonCount(1, 'data');

        $json = $response->json();
        $notificationDb = $json['data'][0];
        $this->assertEquals($notification->toArray($student, 'database'), $notificationDb['data']);

        $response = $this->actingAs($admin)->json('GET', '/api/notifications/' . $friend->getKey());
        $response->assertOk();
        $response->assertJsonCount(1, 'data');

        $json = $response->json();
        $notificationDb = $json['data'][0];
        $this->assertEquals($notification2->toArray($friend, 'database'), $notificationDb['data']);
    }
}
