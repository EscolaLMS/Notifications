<?php

namespace Tests\Api;

use EscolaLms\Notifications\Facades\EscolaLmsNotifications;
use EscolaLms\Notifications\Models\Template;
use EscolaLms\Notifications\Tests\Mocks\TestNotificationWithVariables;
use EscolaLms\Notifications\Tests\Mocks\TestVariables;
use EscolaLms\Notifications\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Testing\TestResponse;

class TemplatesApiTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        EscolaLmsNotifications::registerNotification(TestNotificationWithVariables::class);
    }

    protected function authenticateAsAdmin()
    {
        $this->user = config('auth.providers.users.model')::factory()->create();
        $this->user->guard_name = 'api';
        $this->user->assignRole('admin');
    }

    public function testAdminCanCreateTemplate()
    {
        $this->authenticateAsAdmin();

        $template = Template::factory()->make([
            'type' => 'mail',
            'vars_set' => TestNotificationWithVariables::templateVariablesSetName(),
            'is_default' => false,
            'content' => "content:" . TestVariables::STUDENT_EMAIL,
            'title' => "title:" . TestVariables::STUDENT_EMAIL,
        ])->toArray();

        /** @var TestResponse $response */
        $response = $this->actingAs($this->user, 'api')->postJson(
            '/api/admin/templates',
            $template
        );
        $response->assertStatus(201);

        $data = $response->json();
        $id = $data['data']['id'];

        $response = $this->actingAs($this->user, 'api')->getJson(
            '/api/admin/templates/' . $id,
        );
        $response->assertOk();

        $templateCreated = Template::find($id);

        $this->assertEquals("title:" . TestVariables::STUDENT_EMAIL, $templateCreated->title);
    }

    public function testAdminCanUpdateExistingTemplate()
    {
        $this->authenticateAsAdmin();

        $template = Template::factory()->create([
            'type' => 'mail',
            'vars_set' => TestNotificationWithVariables::templateVariablesSetName(),
            'is_default' => false,
            'content' => "content:" . TestVariables::STUDENT_EMAIL,
            'title' => "title:" . TestVariables::STUDENT_EMAIL,
        ]);

        $templateUpdate = [
            'title' => "title2:" . TestVariables::STUDENT_EMAIL,
        ];

        $response = $this->actingAs($this->user, 'api')->patchJson(
            '/api/admin/templates/' . $template->id,
            $templateUpdate
        );
        $response->assertOk();

        $template->refresh();

        $this->assertEquals($templateUpdate['title'], $template->title);
    }
}
