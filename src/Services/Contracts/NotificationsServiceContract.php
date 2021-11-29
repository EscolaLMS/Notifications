<?php

namespace EscolaLms\Notifications\Services\Contracts;

use EscolaLms\Notifications\Core\NotificationContract;
use EscolaLms\Notifications\Models\Template;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\DatabaseNotification;

interface NotificationsServiceContract
{
    public function registerNotification(string $notificationClass): void;
    public function findTemplateForNotification(NotificationContract $notification, ?string $channel = null): ?Template;
    public function replaceNotificationVariables(NotificationContract $notification, string $content, $notifiable): string;
    public function createDefaultTemplates(?string $notificationClass = null): void;
    public function findDatabaseNotification(string $notificationClass, Model $notifiable, array $data): ?DatabaseNotification;
}
