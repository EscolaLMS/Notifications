<?php

namespace EscolaLms\Notifications\Services\Contracts;

use EscolaLms\Core\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface DatabaseNotificationsServiceContract
{
    public function getUserNotifications(User $user, ?string $event = null): LengthAwarePaginator;
    public function getAllNotifications(?string $event = null): LengthAwarePaginator;
}
