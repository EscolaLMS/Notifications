<?php

namespace EscolaLms\Notifications\Tests\Mocks;

use EscolaLms\Notifications\Core\NotificationAbstract;

class TestNotificationWithoutVariables extends NotificationAbstract
{
    public static function templateVariablesSetName(): string
    {
        return 'notification-without-variables';
    }

    protected function defaultTitle($notifiable, ?string $channel = null): string
    {
        return "default-title";
    }

    protected function defaultContent($notifiable, ?string $channel = null): string
    {
        return "default-content";
    }
}
