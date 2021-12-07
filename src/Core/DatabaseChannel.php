<?php

namespace EscolaLms\Notifications\Core;

use EscolaLms\Notifications\Models\User;
use Illuminate\Notifications\Channels\DatabaseChannel as IlluminateDatabaseChannel;
use Illuminate\Notifications\Notification;

class DatabaseChannel extends IlluminateDatabaseChannel
{
    public function send($notifiable, Notification $notification)
    {
        $notifiable = User::find($notifiable->getKey());
        return parent::send($notifiable, $notification);
    }

    protected function buildPayload($notifiable, Notification $notification)
    {
        return [
            'id' => $notification->id,
            'type' => get_class($notification),
            'event' => $notification instanceof EventNotification ? get_class($notification->getEvent()) : null,
            'data' => $this->getData($notifiable, $notification),
            'read_at' => null,
        ];
    }
}
