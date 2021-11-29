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

    public static function availableVia(): array
    {
        return [
            'mail',
            'database',
            'broadcast',
        ];
    }

    public static function templateVariablesClass(): string
    {
        return TestVariables::class;
    }

    public function toArray($notifiable, ?string $channel = null): array
    {
        return array_merge(parent::toArray($notifiable, $channel), [
            'friend_id' => $this->friend->getKey(),
        ]);
    }

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
