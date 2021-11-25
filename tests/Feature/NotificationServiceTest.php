<?php

namespace Tests\Feature;

use EscolaLms\Notifications\Facades\EscolaLmsNotifications;
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

        /** @var VariablesServiceContract $variablesService */
        $variablesService = app(VariablesServiceContract::class);
        $availableTokens = $variablesService->getAvailableTokens();
        foreach (TestNotificationWithVariables::availableVia() as $channel) {
            $this->assertEquals(TestVariables::getValues(), $availableTokens[$channel][TestNotificationWithVariables::templateVariablesSetName()]);
        }
    }

    public function test_replace_notification_variables()
    {
        $user = $this->makeStudent();
        $friend = $this->makeStudent();

        EscolaLmsNotifications::registerNotification(TestNotificationWithVariables::class);

        $notification = new TestNotificationWithVariables($friend);
        $content = "email:" . TestVariables::STUDENT_EMAIL . PHP_EOL . 'friend:' . TestVariables::FRIEND_EMAIL;
        $replaced_content = EscolaLmsNotifications::replaceNotificationVariables($notification, $content, $user);

        $this->assertEquals("email:" . $user->email . PHP_EOL . "friend:" . $friend->email, $replaced_content);
    }

    public function test_find_template_for_notification()
    {
        $user = $this->makeStudent();
        $friend = $this->makeStudent();

        EscolaLmsNotifications::registerNotification(TestNotificationWithVariables::class);

        $template = Template::factory()->create([
            'type' => 'mail',
            'vars_set' => TestNotificationWithVariables::templateVariablesSetName(),
            'is_default' => true,
            'content' => "content:" . TestVariables::STUDENT_EMAIL,
            'title' => "title:" . TestVariables::STUDENT_EMAIL,
        ]);

        $notification = new TestNotificationWithVariables($friend);

        $template_found = EscolaLmsNotifications::findTemplateForNotification($notification, 'mail');

        $this->assertEquals($template->getKey(), $template_found->getKey());
    }
}
