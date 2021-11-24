<?php

namespace EscolaLms\Notifications\Tests\Mocks;

use EscolaLms\Auth\Models\Group;
use EscolaLms\Notifications\Core\NotificationAbstract;

class TestNotificationWithVariables extends NotificationAbstract
{
    private Group $group;

    public function __construct(Group $group)
    {
        $this->group = $group;
    }

    public static function templateVariablesClass(): string
    {
        return TestVariables::class;
    }

    public static function templateVariablesSetName(): string
    {
        return 'notification-with-variables';
    }

    public function additionalDataForVariables(): array
    {
        return [
            $this->group
        ];
    }

    protected function defaultTitle($notifiable, ?string $channel = null): string
    {
        return "default-title:" . TestVariables::STUDENT_EMAIL;
    }

    protected function defaultContent($notifiable, ?string $channel = null): string
    {
        return "default-content:" . TestVariables::STUDENT_EMAIL;
    }
}
