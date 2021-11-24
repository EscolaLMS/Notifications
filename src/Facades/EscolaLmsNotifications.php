<?php

namespace EscolaLms\Notifications\Facades;

use EscolaLms\Notifications\Core\NotificationContract;
use EscolaLms\Notifications\Models\Template;
use Illuminate\Support\Facades\Facade;

/**
 * @method static void          registerNotification(string $notificationsClass)
 * @method static Template|null findTemplateForNotification(NotificationContract $notification, ?string $channel = 'mail')
 * @method static string        replaceNotificationVariables(NotificationContract $notification, string $content, $notifiable)
 * 
 * @see \EscolaLms\Notifications\Services\NotificationsService
 */
class EscolaLmsNotifications extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'escola_notifications_facade';
    }
}
