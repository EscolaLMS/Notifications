<?php

namespace EscolaLms\Notifications\Services\Contracts;

use EscolaLms\Notifications\Core\NotificationContract;
use EscolaLms\Notifications\Models\Template;

interface NotificationsServiceContract
{
    public function registerNotification(string $notificationClass): void;
    public function findTemplateForNotification(NotificationContract $notification, ?string $channel = 'mail'): ?Template;
    public function replaceNotificationVariables(NotificationContract $notification, string $content, $notifiable): string;
}
