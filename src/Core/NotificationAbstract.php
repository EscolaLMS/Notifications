<?php

namespace EscolaLms\Notifications\Core;

use EscolaLms\Notifications\Core\Traits\NotificationDefaultImplementation;
use Illuminate\Notifications\Notification as IlluminateNotification;

abstract class NotificationAbstract extends IlluminateNotification implements NotificationContract
{
    use NotificationDefaultImplementation;
}
