<?php

namespace Tests\Feature;

use EscolaLms\Auth\Models\Group;
use EscolaLms\Notifications\Facades\EscolaLmsNotifications;
use EscolaLms\Notifications\Tests\Mocks\TestNotificationWithoutVariables;
use EscolaLms\Notifications\Tests\Mocks\TestNotificationWithVariables;
use EscolaLms\Notifications\Tests\Mocks\TestVariables;
use EscolaLms\Notifications\Tests\TestCase;
use EscolaLms\Templates\Services\Contracts\VariablesServiceContract;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use EscolaLms\Core\Tests\CreatesUsers;
use EscolaLms\Notifications\Models\Template;

class NotificationServiceTest extends TestCase
{
    use DatabaseTransactions;
    use CreatesUsers;

    public function test_register_notification()
    {
        EscolaLmsNotifications::registerNotification(TestNotificationWithVariables::class);
        EscolaLmsNotifications::registerNotification(TestNotificationWithoutVariables::class);

        /** @var VariablesServiceContract $variablesService */
        $variablesService = app(VariablesServiceContract::class);
        $availableTokens = $variablesService->getAvailableTokens();
        foreach (TestNotificationWithVariables::availableVia() as $channel) {
            $this->assertEquals(TestVariables::getValues(), $availableTokens[$channel]['notification-with-variables']);
        }
    }

    public function test_replace_notification_variables()
    {
        $group = Group::factory()->create();
        $user = $this->makeStudent();

        EscolaLmsNotifications::registerNotification(TestNotificationWithVariables::class);

        $notification = new TestNotificationWithVariables($group);
        $content = "email:" . TestVariables::STUDENT_EMAIL . PHP_EOL . 'group:' . TestVariables::STUDENT_GROUP;
        $replaced_content = EscolaLmsNotifications::replaceNotificationVariables($notification, $content, $user);

        $this->assertEquals("email:" . $user->email . PHP_EOL . "group:" . $group->name, $replaced_content);
    }

    public function test_find_template_for_notification()
    {
        $group = Group::factory()->create();
        $user = $this->makeStudent();

        EscolaLmsNotifications::registerNotification(TestNotificationWithVariables::class);

        $template = Template::factory()->create([
            'type' => 'mail',
            'vars_set' => TestNotificationWithVariables::templateVariablesSetName(),
            'is_default' => true,
            'content' => "content:" . TestVariables::STUDENT_EMAIL,
            'title' => "title:" . TestVariables::STUDENT_EMAIL,
        ]);

        $notification = new TestNotificationWithVariables($group);

        $template_found = EscolaLmsNotifications::findTemplateForNotification($notification, 'mail');

        $this->assertEquals($template->getKey(), $template_found->getKey());
    }
}
