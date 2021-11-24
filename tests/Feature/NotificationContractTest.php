<?php

namespace Tests\Feature;

use EscolaLms\Auth\Models\Group;
use EscolaLms\Auth\Models\User;
use EscolaLms\Notifications\Facades\EscolaLmsNotifications;
use EscolaLms\Notifications\Tests\Mocks\TestNotificationWithVariables;
use EscolaLms\Notifications\Tests\Mocks\TestVariables;
use EscolaLms\Notifications\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use EscolaLms\Core\Tests\CreatesUsers;
use EscolaLms\Notifications\Models\Template;
use Illuminate\Support\Facades\Notification;

class NotificationContractTest extends TestCase
{
    use DatabaseTransactions;
    use CreatesUsers;

    public Group $group;
    public User $user;

    protected function setUp(): void
    {
        parent::setUp();

        EscolaLmsNotifications::registerNotification(TestNotificationWithVariables::class);

        foreach (TestNotificationWithVariables::availableVia() as $channel) {
            Template::factory()->create([
                'type' => $channel,
                'vars_set' => TestNotificationWithVariables::templateVariablesSetName(),
                'is_default' => true,
                'content' => $channel . "-content:" . TestVariables::STUDENT_EMAIL,
                'title' => $channel . "-title:" . TestVariables::STUDENT_EMAIL,
            ]);
        }

        $this->group = Group::factory()->create();
        $this->user = $this->makeStudent();
    }

    public function test_sending_notification()
    {
        Notification::fake();

        $notification = new TestNotificationWithVariables($this->group);
        $this->user->notify($notification);

        Notification::assertSentTo($this->user, TestNotificationWithVariables::class);
    }

    public function test_notification_contract()
    {
        $notification = new TestNotificationWithVariables($this->group);

        $array = $notification->toArray($this->user, null);

        $this->assertEquals('default-title:' . $this->user->email, $array['title']);

        $mail = $notification->toMail($this->user);

        $this->assertEquals('mail-title:' . $this->user->email, $mail->data()['subject']);

        $database = $notification->toDatabase($this->user);

        $this->assertEquals('database-title:' . $this->user->email, $database->data['title']);

        $broadcast = $notification->toBroadcast($this->user);

        $this->assertEquals('broadcast-title:' . $this->user->email, $broadcast->data['title']);

        $additional = $notification->additionalDataForVariables();

        $this->assertEquals(Group::class, get_class($additional[0]));
        $this->assertEquals($this->group->getKey(), $additional[0]->getKey());
    }
}
