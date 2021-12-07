<?php

namespace EscolaLms\Notifications\Models;

use EscolaLms\Notifications\Casts\DatabaseNotificationData;
use Illuminate\Notifications\DatabaseNotification as IlluminateDatabaseNotification;

class DatabaseNotification extends IlluminateDatabaseNotification
{
    protected $casts = [
        'data' => DatabaseNotificationData::class,
        'read_at' => 'datetime',
    ];
}
