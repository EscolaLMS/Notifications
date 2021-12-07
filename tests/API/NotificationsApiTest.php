<?php

namespace Tests\Api;

use EscolaLms\Core\Tests\CreatesUsers;
use EscolaLms\Notifications\Database\Seeders\NotificationsPermissionsSeeder;
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

        $response = $this->actingAs($student)->json('GET', '/api/notifications/');
        $response->assertOk();

        $json = $response->json();
        $notificationDb = $json['data'][0];

        $this->assertEquals($friend->getKey(), $notificationDb['data']['friend']['id']);
    }

    public function test_admin_can_see_any_notifications()
    {
        $admin = $this->makeAdmin();
        $student = $this->makeStudent();
        $friend = $this->makeStudent();

        event(new TestEvent($student, $friend, 'foo'));
        event(new TestEvent($friend, $student, 'foo'));

        $response = $this->actingAs($admin)->json('GET', '/api/notifications/' . $student->getKey());
        $response->assertOk();
        $response->assertJsonCount(1, 'data');

        $json = $response->json();
        $notificationDb = $json['data'][0];
        $this->assertEquals($friend->getKey(), $notificationDb['data']['friend']['id']);

        $response = $this->actingAs($admin)->json('GET', '/api/notifications/' . $friend->getKey());
        $response->assertOk();
        $response->assertJsonCount(1, 'data');

        $json = $response->json();
        $notificationDb = $json['data'][0];
        $this->assertEquals($student->getKey(), $notificationDb['data']['friend']['id']);
    }
}
