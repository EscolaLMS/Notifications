<?php

namespace EscolaLms\Notifications\Tests\Mocks;

use EscolaLms\Core\Models\User;
use EscolaLms\Notifications\Core\NotificationAbstract;

class TestNotificationWithVariables extends NotificationAbstract
{
    private User $friend;

    public function __construct(User $friend)
    {
        $this->friend = $friend;
    }

    public static function templateVariablesClass(): string
    {
        return TestVariables::class;
    }

    /*
    public static function templateVariablesSetName(): string
    {
        return 'notification-with-variables';
    }
    */

    public function additionalDataForVariables($notifiable): array
    {
        return [
            $this->friend
        ];
    }

    public static function defaultTitleTemplate(): string
    {
        return "default-title:" . TestVariables::STUDENT_EMAIL;
    }

    public static function defaultContentTemplate(): string
    {
        return "default-content:" . TestVariables::STUDENT_EMAIL;
    }
}
